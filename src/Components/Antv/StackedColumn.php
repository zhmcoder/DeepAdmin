<?php

namespace Andruby\DeepAdmin\Components\Antv;

use SmallRuralDog\Admin\Components\Antv\Line;

class StackedColumn extends Line
{
    protected $componentName = "AntvStackedColumn";
    protected $canvasId;
    protected $data;
    protected $config;

    public static function make()
    {
        return new StackedColumn();
    }

}
