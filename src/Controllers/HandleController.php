<?php

namespace Andruby\DeepAdmin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HandleController extends Controller
{
    public function uploadFile(Request $request)
    {
        try {
            \Admin::validatorData($request->all(), [
                'file' => 'mimes:' . config('deep_admin.upload.file', 'doc,docx,mp3,mp4,apk,xlsx')
            ]);
            return $this->upload($request);
        } catch (\Exception $exception) {
            return \Admin::responseError($exception->getMessage());
        }
    }

    public function uploadImage(Request $request)
    {
        try {
            \Admin::validatorData($request->all(), [
                'file' => 'mimes:' . config('deep_admin.upload.image', 'jpeg,bmp,png,gif,jpg')
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
            $uniqueName = $request->input('uniqueName', config('deep_admin.upload.uniqueName', false));
            $disk = config('deep_admin.upload.disk');
            $name = $file->getClientOriginalName();
            if ($uniqueName == "true" || $uniqueName == true) {
                $path = $file->store($path, $disk);
            } else {
                $path = $file->storeAs($path, $name, $disk);
            }

            if (config('filesystems.disks.' . $disk . '.isSign')) {
                $url = \Storage::disk($disk)->signUrl($path, 60, ['x-oss-process' => 'image/circle,r_100']);
            } else {
                $url = \Storage::disk($disk)->url($path);
            }

            $data = [
                'path' => $path,
                'name' => $name,
                'url' => $url
            ];
            return \Admin::response($data);
        } catch (\Exception $exception) {
            return \Admin::responseError($exception->getMessage());
        }
    }

}
