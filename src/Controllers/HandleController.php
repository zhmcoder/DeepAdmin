<?php

namespace Andruby\DeepAdmin\Controllers;


use Andruby\DeepAdmin\Models\Files;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HandleController extends Controller
{

    public function uploadFile(Request $request)
    {
        try {
            /*
            \Admin::validatorData($request->all(), [
                'file' => 'mimes:' . config('admin.upload.mimes', 'jpeg,bmp,png,gif,jpg')
            ]);
            */
            return $this->upload($request);
        } catch (\Exception $exception) {
            return \Admin::responseError($exception->getMessage());
        }

    }

    public function uploadImage(Request $request)
    {
        try {
            \Admin::validatorData($request->all(), [
                'file' => 'image'
            ]);

            return $this->upload($request);

        } catch (\Exception $exception) {
            return \Admin::responseError($exception->getMessage());
        }
    }


    protected function upload(Request $request)
    {
        try {
            $file = $request->file('file');
            $type = $request->file('type');
            $path = $request->input('path', 'images');
            $path_1 = $request->input('path', 'images');
            $uniqueName = $request->input('uniqueName', config('admin.upload.uniqueName', false));
            $disk = config('admin.upload.disk');
            $name = $file->getClientOriginalName();

            $file_md5 = md5_file($file->getRealPath());
            $file_info = Files::where(['md5' => $file_md5])->first();
            if($file_info){
                $data = [
                    'id' => $file_info['id'],//兼容vue-element-admin的处理逻辑，传的是文件存储的id
                    'path' => $file_info['id'].'',
                    'name' => $file_info['name'],
                    'url' => \Storage::disk($file_info['disk'])->url($file_info['path']),
                ];
            }else{
                if ($uniqueName == "true" || $uniqueName == true) {
                    $path = $file->store($path, $disk);
                } else {
                    $path = $file->storeAs($path, $name, $disk);
                }
                $file_data['name'] = $name;
                $file_data['path'] = $path;
                $file_data['disk'] = $disk;
                $file_data['type'] = $type;
                $file_data['md5'] = $file_md5;
                $file_data['sha1'] =sha1_file($file->getRealPath());
                $file_info = Files::create($file_data);

                $data = [
                    'id' => $file_info['id'],
                    'path' => $path,
                    'name' => $name,
                    'url' => \Storage::disk($disk)->url($path),
                    'test' => 'test new',
                ];
            }

            return \Admin::response($data);
        } catch (\Exception $exception) {
            return \Admin::responseError($exception->getMessage());
        }

    }

}
