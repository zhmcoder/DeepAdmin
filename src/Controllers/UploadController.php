<?php

namespace Andruby\DeepAdmin\Controllers;

use Andruby\DeepAdmin\Traits\UploadTraits;
use Illuminate\Http\Request;

class UploadController extends AdminController
{
    use UploadTraits;

    public function images(Request $request)
    {
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
            $url = \Storage::disk($disk)->signUrl($path, 60);
        } else {
            $url = \Storage::disk($disk)->url($path);
        }

        // 返回数据。wangEditor3 需要用到的数据 json格式的
        $data = [
            'errno' => 0,
            'data' => [
                "{$url}",
            ],
        ];

        // 返回数据
        echo json_encode($data);
    }


    public function images_v4(Request $request)
    {
        $amount = request('amount', 100);

        $images = [];
        for ($i = 0; $i < $amount; $i++) {
            if (!isset($_FILES["file" . $i])) {
                break;
            }

            $file = $request->file('file' . $i);
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
                $url = \Storage::disk($disk)->signUrl($path, 60);
            } else {
                $url = \Storage::disk($disk)->url($path);
            }

            $images[] = [
                'url' => $url,
                'alt' => '',
                'href' => '',
            ];
        }

        $data = [
            'errno' => 0,
            'data' => $images,
        ];

        // 返回数据
        echo json_encode($data);
    }

}
