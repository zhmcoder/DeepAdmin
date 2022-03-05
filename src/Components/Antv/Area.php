<?php

namespace Andruby\DeepAdmin\Components\Antv;

use Illuminate\Support\Str;
use Andruby\DeepAdmin\Components\Component;

class Area extends Line
{
    protected $componentName = "AntvArea";
    protected $canvasId;
    protected $data;
    protected $config;


    public static function make()
    {
        return new Area();
    }


}
