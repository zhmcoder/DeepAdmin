<?php

namespace Andruby\DeepAdmin\Components\Echarts;

use SmallRuralDog\Admin\Components\Component;

class GaugeChart extends Component
{
    protected $componentName = "GaugeChart";
    protected $canvasId;
    protected $data;
    protected $config;

    public static function make()
    {
        return new GaugeChart();
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
