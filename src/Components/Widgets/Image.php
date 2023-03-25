<?php

namespace Andruby\DeepAdmin\Components\Widgets;

use Andruby\DeepAdmin\Components\Component;

class Image extends Component
{
    protected $componentName = "Image";
    protected $srcList;

    public static function make()
    {
        return new Image();
    }

    public function srcList($srcList)
    {
        $this->srcList = $srcList;
        return $this;
    }

}
