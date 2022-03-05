<?php

namespace Andruby\DeepAdmin\Components\Antv;

use Andruby\DeepAdmin\Components\Antv\Line;

class Pie extends Line
{
    protected $componentName = "AntvPie";
    protected $canvasId;
    protected $data;
    protected $config;

    public static function make()
    {
        return new Pie();
    }

}
