<?php

namespace Andruby\DeepAdmin\Components\Widgets;

use Andruby\DeepAdmin\Components\Component;

class ImageList extends Component
{
    protected $componentName = "ImageList";
    protected $srcList;

    public static function make()
    {
        return new ImageList();
    }

    public function srcList($srcList)
    {
        $this->srcList = $srcList;
        return $this;
    }

}
