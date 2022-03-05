<?php

namespace Andruby\DeepAdmin\Components\Grid;

use Andruby\DeepAdmin\Components\Component;
use Andruby\DeepAdmin\Components\Grid\Boole;
use Andruby\DeepAdmin\Grid;


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
