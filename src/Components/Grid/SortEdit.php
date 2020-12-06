<?php

namespace Andruby\DeepAdmin\Components\Grid;

use SmallRuralDog\Admin\Components\Component;
use SmallRuralDog\Admin\Components\Grid\Boole;


class SortEdit extends Component
{
    protected $componentName = "SortEdit";
    protected $action = null;

    public static function make($value = null)
    {
        return new SortEdit($value);
    }

    public function action($action)
    {
        $this->action = $action;
        return $this;
    }


}
