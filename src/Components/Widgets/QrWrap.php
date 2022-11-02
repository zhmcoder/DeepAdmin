<?php

namespace Andruby\DeepAdmin\Components\Widgets;

use Andruby\DeepAdmin\Components\Component;

class QrWrap extends Component
{
    protected $componentName = 'QrWrap';

    public static function make()
    {
        return new QrWrap();
    }
}
