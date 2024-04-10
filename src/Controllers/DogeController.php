<?php

namespace Andruby\DeepAdmin\Controllers;

use Andruby\DeepAdmin\Auth\Database\OperationLog;

use Andruby\DeepAdmin\Components\Form\Input;
use Andruby\DeepAdmin\Components\Grid\Avatar;
use Andruby\DeepAdmin\Components\Grid\Tag;
use Andruby\DeepAdmin\Form;
use Andruby\DeepAdmin\Grid;
use Andruby\DeepAdmin\Services\DogeApiService;

class DogeController extends AdminController
{
    protected function key_info()
    {
//        $key_info = file_get_contents('https://api.dogecloud.com/uploadServiceDemo.json');
//        $key_info = json_decode($key_info, true);
        $token_info = DogeApiService::instance()->tmp_token();
        $token_info = $token_info['data'];
        $key_info['credentials'] = $token_info['Credentials'];
        $key_info['s3Bucket'] = $token_info['Buckets'][0]['s3Bucket'];
        $key_info['s3Endpoint'] = $token_info['Buckets'][0]['s3Endpoint'];
        $key_info['keyPrefix'] = 'videos/*';
        $key_info['cdn_host'] = 'https://cdn.splash.lifeano.cn';
//        $key_info['auth_key'] = DogeApiService::instance()->auth_key('/' . 'videos/1app-1.0.4.apk',
//            time() + config('xunji.doge.auth_expired', 2 * 60 * 60));
        return \Admin::response($key_info);
    }

    protected function form()
    {
        $form = new Form(new OperationLog());

        return $form;
    }
}
