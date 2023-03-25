<?php

namespace Andruby\DeepAdmin\Components\Widgets;

use Andruby\DeepAdmin\Components\Component;

class Image extends Component
{
    protected $componentName = "Image";
    protected $image_list;

    public static function make()
    {
        return new Image();
    }

    public function image_list($image_list)
    {
        $this->image_list = $image_list;
        return $this;
    }

}
