<?php

namespace Andruby\DeepAdmin\Controllers;

use SmallRuralDog\Admin\Controllers\AdminController;

class UploadController extends AdminController
{
    public function images()
    {
        // 图片文件的生成
        // localResizeIMG压缩后的图片都是jpeg格式
        $saveName = date('YmdHis', time()) . mt_rand(0, 9999) . '.jpeg';

        // 生成文件
        $savePath = storage_path("app/public/images/" . $saveName);

        // 生成一个URL获取图片的地址
        $url = env('APP_URL') . '/storage/images/' . $saveName;

        // 返回数据。wangEditor3 需要用到的数据 json格式的
        $data = [
            'errno' => 0,
            'data' => [
                "{$url}",
            ],
        ];

        // 图片文件移动。
        move_uploaded_file($_FILES["file"]["tmp_name"], $savePath);

        // 返回数据
        echo json_encode($data);
    }

}
