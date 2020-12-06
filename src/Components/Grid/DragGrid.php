<?php

namespace Andruby\DeepAdmin\Components\Grid;

use SmallRuralDog\Admin\Components\Component;
use SmallRuralDog\Admin\Components\Grid\Boole;
use SmallRuralDog\Admin\Grid;


class DragGrid extends Grid
{
    protected $componentName = "DragGrid";
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
