<?php

namespace Andruby\DeepAdmin\Components\Antv;

use Andruby\DeepAdmin\Components\Antv\Line;

class GroupColumn extends Line
{
    protected $componentName = "AntvGroupColumn";
    protected $canvasId;
    protected $data;
    protected $config;

    public static function make()
    {
        return new GroupColumn();
    }

}
