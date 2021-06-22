<?php

namespace Andruby\DeepAdmin\Components\Antv;

use SmallRuralDog\Admin\Components\Antv\Line;

class DualAxes extends Line
{
    protected $componentName = "AntvDualAxes";
    protected $canvasId;
    protected $data;
    protected $config;

    public static function make()
    {
        return new DualAxes();
    }

}
