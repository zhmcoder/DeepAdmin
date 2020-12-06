<?php

namespace Andruby\DeepAdmin\Components\Echarts;

use SmallRuralDog\Admin\Components\Component;

class LineChart extends Component
{
    protected $componentName = "LineChart";
    protected $canvasId;
    protected $data;
    protected $config;

    public static function make()
    {
        return new LineChart();
    }

    public function data($data) {
      if ($data instanceof \Closure) {
        $this->data = call_user_func($data);
      } else {
        $this->data = $data;
      }
      return $this;
    }

}
