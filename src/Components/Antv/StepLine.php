<?php

namespace Andruby\DeepAdmin\Components\Antv;

class StepLine extends Line
{
    protected $componentName = "AntvStepLine";
    protected $canvasId;
    protected $data;
    protected $config;


    public static function make()
    {
        return new StepLine();
    }


}
