<?php


namespace Andruby\DeepAdmin\Components\Grid;


use Andruby\DeepAdmin\Components\Component;

class Boole extends Component
{
    protected $componentName = "Boole";


    public static function make($value = null)
    {
        return new Boole($value);
    }

}
