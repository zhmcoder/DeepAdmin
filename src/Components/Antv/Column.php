<?php

namespace Andruby\DeepAdmin\Components\Antv;

class Column extends Line
{
    protected $componentName = "AntvColumn";
    protected $canvasId;
    protected $data;
    protected $config;


    public static function make()
    {
        return new Column();
    }


}
