<?php

namespace Andruby\DeepAdmin\Components\Echarts;

use Illuminate\Support\Str;
use SmallRuralDog\Admin\Components\Component;

class NormalBarChart extends Component
{
    protected $componentName = "NormalBarChart";
    protected $canvasId;
    protected $data;
    protected $config;

    public function __construct()
    {
        $this->canvasId = Str::random();
    }

    public static function make()
    {
        return new NormalBarChart();
    }

    public function data($data)
    {
        if ($data instanceof \Closure) {
            $this->data = call_user_func($data);
        } else {
            $this->data = $data;
        }
        return $this;
    }

}
