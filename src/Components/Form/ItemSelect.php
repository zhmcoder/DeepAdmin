<?php
namespace Andruby\DeepAdmin\Components\Form;

use Andruby\DeepAdmin\Components\Component;

class ItemSelect extends Component
{
    protected $componentName = "ItemSelect";

    public static function make()
    {
        return new ItemSelect();
    }

}
