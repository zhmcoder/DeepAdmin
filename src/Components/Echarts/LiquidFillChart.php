<?php

namespace Andruby\DeepAdmin\Components\Echarts;

use SmallRuralDog\Admin\Components\Component;

class LiquidFillChart extends Component
{
    protected $componentName = "LiquidFillChart";
    protected $canvasId;
    protected $data;
    protected $config;

    public static function make()
    {
        return new LiquidFillChart();
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
