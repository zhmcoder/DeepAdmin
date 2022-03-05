<?php


namespace Andruby\DeepAdmin\Components\Widgets;


use Andruby\DeepAdmin\Components\Attrs\Button;
use Andruby\DeepAdmin\Components\Component;

class DialogButton extends Component
{

    use Button;

    protected $componentName = 'DialogButton';


    public static function make()
    {
        return new DialogButton();
    }

}
