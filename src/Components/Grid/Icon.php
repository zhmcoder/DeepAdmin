<?php


namespace Andruby\DeepAdmin\Components\Grid;


use Andruby\DeepAdmin\Components\GridComponent;

class Icon extends GridComponent
{
    protected $componentName = "Icon";

    static public function make($value = null)
    {
        return new Icon($value);
    }

}
