<?php

namespace Andruby\DeepAdmin\Components\Antv;

use Andruby\DeepAdmin\Components\Antv\Line;

class Graph extends Line
{
    protected $componentName = "AntvGraph";
    protected $canvasId;
    protected $data;
    protected $config;

    public static function make()
    {
        return new Graph();
    }

}
