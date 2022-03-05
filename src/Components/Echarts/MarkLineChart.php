<?php

namespace Andruby\DeepAdmin\Components\Echarts;

use Illuminate\Support\Str;
use Andruby\DeepAdmin\Components\Component;

class MarkLineChart extends Component
{
    protected $componentName = "MarkLineChart";
    protected $canvasId;
    protected $data;
    protected $config;

    public function __construct()
    {
        $this->canvasId = Str::random();
    }

    public static function make()
    {
        return new MarkLineChart();
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
