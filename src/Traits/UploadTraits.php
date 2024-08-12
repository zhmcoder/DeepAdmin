<?php

namespace Andruby\DeepAdmin\Traits;

use Andruby\DeepAdmin\Models\Files;
use Illuminate\Http\Request;

trait UploadTraits
{
    public function uploadPdf(Request $request)
    {
        try {
            $mimes = config('deep_admin.upload.pdf', '.pdf');
            \Admin::validatorData($request->all(), [
                'file' => 'mimes:' . str_replace('.', '', $mimes)
            ]);
            return $this->upload($request);
        } catch (\Exception $exception) {
            if (method_exists($this, 'responseError')) {
                $this->responseError($exception->getMessage());
            } else {
                return \Admin::responseError($exception->getMessage());
            }
        }
    }

    public function uploadXlsx(Request $request)
    {
        try {
            $mimes = config('deep_admin.upload.xlsx', '.xls,.xlsx');
            \Admin::validatorData($request->all(), [
                'file' => 'mimes:' . str_replace('.', '', $mimes)
            ]);
            return $this->upload($request);
        } catch (\Exception $exception) {
            if (method_exists($this, 'responseError')) {
                $this->responseError($exception->getMessage());
            } else {
                return \Admin::responseError($exception->getMessage());
            }
        }
    }

    public function uploadFile(Request $request)
    {
        try {
            $mimes = config('deep_admin.upload.file', '.doc,.docx,.mp3,.mp4,.apk,.xlsx,.xls');
            \Admin::validatorData($request->all(), [
                'file' => 'mimes:' . str_replace('.', '', $mimes)
            ]);
            return $this->upload($request);
        } catch (\Exception $exception) {
            if (method_exists($this, 'responseError')) {
                $this->responseError($exception->getMessage());
            } else {
                return \Admin::responseError($exception->getMessage());
            }
        }
    }

    public function uploadImage(Request $request)
    {
        try {
            $mimes = config('deep_admin.upload.image', '.jpeg,.bmp,.png,.gif,.jpg');
            \Admin::validatorData($request->all(), [
                'file' => 'mimes:' . str_replace('.', '', $mimes)
            ]);
            return $this->upload($request);
        } catch (\Exception $exception) {
            if (method_exists($this, 'responseError')) {
                $this->responseError($exception->getMessage());
            } else {
                return \Admin::responseError($exception->getMessage());
            }
        }
    }

    protected function upload(Request $request)
    {
        try {
            $file = $request->file('file');
            $type = $request->file('type');
            $path = $request->input('path', 'images');
            $path_1 = $request->input('path', 'images');
            $uniqueName = $request->input('uniqueName', config('deep_admin.upload.uniqueName', false));
            $disk = $request->input('disk', config('deep_admin.upload.disk'));
            $name = $file->getClientOriginalName();

            $file_md5 = md5_file($file->getRealPath());
            $file_info = Files::where(['md5' => $file_md5])->first();
            if ($file_info) {
                $data = [
                    'id' => $file_info['id'],//兼容vue-element-admin的处理逻辑，传的是文件存储的id
                    'path' => $file_info['path'],
                    'name' => $file_info['name'],
                    'url' => \Storage::disk($file_info['disk'])->url($file_info['path']),
                    'host' => \Storage::disk($disk)->url('')
                ];
            } else {
                if ($uniqueName === "true" || $uniqueName === true) {
                    $path = $file->store($path, $disk);
                } else {
                    $path = $file->storeAs($path, $name, $disk);
                }
                $file_data['name'] = $name;
                $file_data['path'] = $path;
                $file_data['disk'] = $disk;
                $file_data['type'] = $type;
                $file_data['md5'] = $file_md5;
                $file_data['sha1'] = sha1_file($file->getRealPath());
                $file_info = Files::create($file_data);

                $data = [
                    'id' => $file_info['id'],
                    'path' => $path,
                    'name' => $name,
                    'url' => \Storage::disk($disk)->url($path),
                    'host' => \Storage::disk($disk)->url('')
                ];
            }

            if (method_exists($this, 'responseSuccess')) {
                $this->responseSuccess($data);
            } else {
                return \Admin::response($data);
            }
        } catch (\Exception $exception) {
            if (method_exists($this, 'responseError')) {
                $this->responseError($exception->getMessage());
            } else {
                return \Admin::responseError($exception->getMessage());
            }
        }
    }
}
