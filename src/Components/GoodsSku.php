<?php

namespace Andruby\DeepAdmin\Components;

use SmallRuralDog\Admin\Components\Component;
use SmallRuralDog\Admin\Components\Form\Upload;
use SmallRuralDog\Admin\Components\Grid\Image;


class GoodsSku extends Component
{


    protected $componentName = "GoodsSku";


    protected $uploadComponent;
    protected $imageComponent;


    public function __construct($value = [])
    {

    }

    public static function make($value = [])
    {
        return new GoodsSku($value);
    }

    public function getValue($data)
    {
        return [
            'goods_attrs' => [],
            'goods_sku_list' => []
        ];
    }



}
