<?php


namespace Andruby\DeepAdmin\Components\Widgets;


use Andruby\DeepAdmin\Components\Attrs\ElDialog;
use Andruby\DeepAdmin\Components\Component;

class Dialog extends Component
{
    use ElDialog;

    protected $componentName = "Dialog";


    public static function make()
    {
        return new Dialog();
    }

}
