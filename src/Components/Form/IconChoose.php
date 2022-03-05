<?php

namespace Andruby\DeepAdmin\Components\Form;

use Andruby\DeepAdmin\Components\Component;

class IconChoose extends Component
{
    protected $componentName = "IconChoose";

    public static function make($value = null)
    {
        return new IconChoose($value);
    }

}
