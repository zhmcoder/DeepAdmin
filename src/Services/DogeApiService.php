<?php

namespace Andruby\DeepAdmin\Services;


/**
 * @method static DogeApiService instance()
 *
 * Class DogeCloudService
 * @package App\Api\Services
 */
class DogeApiService
{
    public static function __callStatic($method, $params): DogeApiService
    {
        return new self();
    }

    private function doge_cloud_api($apiPath, $data = array(), $jsonMode = false)
    {

        $accessKey = env('DOGE_ACCESS_KEY');
        $secretKey = env('DOGE_SECRET_KEY');
        $body = $jsonMode ? json_encode($data) : http_build_query($data);

        $signStr = $apiPath . "\n" . $body;
        $sign = hash_hmac('sha1', $signStr, $secretKey);
        $Authorization = "TOKEN " . $accessKey . ":" . $sign;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.dogecloud.com" . $apiPath);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // 如果是本地调试，或者根本不在乎中间人攻击，可以把这里的 1 和 2 修改为 0，就可以避免报错
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 建议实际使用环境下 cURL 还是配置好本地证书
        if (isset($data) && $data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: ' . ($jsonMode ? 'application/json' : 'application/x-www-form-urlencoded'),
            'Authorization: ' . $Authorization
        ));
        $ret = curl_exec($ch);
        curl_close($ch);
        return json_decode($ret, true);
    }

    public function video_detail($data)
    {
        $url = '/video/info.json?' . http_build_query($data);
        return $this->doge_cloud_api($url);
    }

    public function video_url($vid, $platform, $ip)
    {
        $data['platform'] = $platform;
        $data['vid'] = $vid;
        $data['ip'] = $ip;
        $data['playToken'] = $this->play_token($vid);
        $url = '/video/streams.json?' . http_build_query($data);
        $result = $this->doge_cloud_api($url);
        if ($result['code'] == 200) {
            return $result;
        } else {
            doge_log_info('video_url url = ' . $url . ' result = ' . json_encode($result));
            return null;
        }
    }

    public function tmp_token($path = 'videos')
    {
        $data['channel'] = 'OSS_UPLOAD';
        $data['ttl'] = env('DOGE_AUTH_EXPIRED', 7200);
        $data['scopes'] = [env('DOGE_BUCKET', 'xunji') . ':' . $path . '/*'];

        $url = '/auth/tmp_token.json';
        $result = $this->doge_cloud_api($url, $data, true);
        debug_log_info('video_url url = ' . $url . ' result = ' . json_encode($result));
        if ($result['code'] == 200) {
            return $result;
        } else {
            return null;
        }

    }

    public function import_url($name, $url, $callbackString)
    {
        $data['url'] = $url;
        $data['vn'] = $name;
        $data['callbackString'] = $callbackString;
        $url = '/console/vfetch/add.json?' . http_build_query($data);
        $result = $this->doge_cloud_api($url);
        if ($result['code'] == 200) {
            return $result['data'];
        } else {
            doge_log_info('import_url url = ' . $url . ' result = ' . json_encode($result));
            return null;
        }
    }

    public function import_file_url($hash, $url)
    {
        $data['url'] = $url;
        $data['key'] = $hash;
        $data['bucket'] = 'xunji-app';
        $data['callbackurl'] = env('DOGE_CALLBACK_URL', 'http://api.sandbox.lifeano.cn') . '/api/DogeCloud/callback?video_id=' . $hash;
        $url = '/oss/fetch.json?';
        $result = $this->doge_cloud_api($url, $data, true);
        debug_log_info(json_encode($result));
        if ($result['code'] == 200) {
            return $result['data'];
        } else {
            doge_log_info('import_url url = ' . $url . ' result = ' . json_encode($result));
            return null;
        }
    }

    //

    public function query_upload_status($id)
    {
        $data['id'] = $id;
        $url = ' /oss/fetch/query.json?' . http_build_query($data);
        $result = $this->doge_cloud_api($url);
        echo json_encode($result);
        exit();
    }

    public function file_info($id)
    {
        $data = ['a3bd0430ce261908619bcc31a09a54fe'];
        $query['bucket'] = 'xunji-app';
        $url = '/oss/file/info.json?' . http_build_query($query);
        $result = $this->doge_cloud_api($url, $data, true);
        echo json_encode($result);
        exit();
    }

    private function play_token($vcode)
    {
        $SecretKey = config('xunji.doge.secret_key');
        $myPolicy = json_encode(array(
            'e' => time() + config('xunji.doge.token_expired', 120),
            'v' => $vcode,
            'full' => true
        ));

        $iv = random_bytes(16);
        $encodedData = base64_encode(openssl_encrypt($myPolicy, 'aes-256-cfb', $SecretKey, OPENSSL_RAW_DATA, $iv));
        $hashedData = base64_encode(hash_hmac('sha1', $myPolicy, $SecretKey, true));
        $encodedData = $encodedData . ':' . base64_encode($iv) . ':' . $hashedData;

        return strtr($encodedData, array('+' => '-', '/' => '_'));;
    }


    public function auth_key($path, $expired_time)
    {
        $auth_key = config('xunji.doge.auth_key', 'q4ZKgnhIdhyXfer6IUCymCgYIARAAG8');;

//        $key = 'q4ZKgnhIdhyXfer6IUCymCgYIARAAG8';
//        $path = '/test/a.jpg';
//        $expired_time = ;
        $rand = $this->rand_str(32);
        $uid = 0;
        $md5hash = md5($path . '-' . $expired_time . '-' . $rand . '-' . $uid . '-' . $auth_key);


        return $expired_time . '-' . $rand . '-' . $uid . '-' . $md5hash;

        //1582638821-HRtzwh43en96cih4ZBk67ePCU4esNPGx-0-4a98a6d76ca0acc83469b68612551aa3
        //1582638821-HRtzwh43en96cih4ZBk67ePCU4esNPGx-0-4a98a6d76ca0acc83469b68612551aa3
    }


    function rand_str($length)
    {
        $str = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $keys = array_rand($str, $length);
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $str[$keys[$i]];
        }
        return $password;
    }

    public function set_mime($data)
    {
        $query['bucket'] = 'xunji-app';
        $query['mime'] = base64_encode('audio/mpeg');

        $url = '/oss/file/mime.json?' . http_build_query($query);
        doge_log_info($url);
        $result = $this->doge_cloud_api($url, $data, true);
        return $result;
    }

}
