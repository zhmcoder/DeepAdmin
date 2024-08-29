<?php

namespace Andruby\DeepAdmin\Components\Grid;

use Andruby\DeepAdmin\Components\Component;

class SelectEdit extends Component
{
    protected $componentName = "SelectEdit";
    protected $action = null;
    protected $width = 60;
    protected $field = 'sort';
    protected $showSummary = false;
    protected $options = [];

    public static function make($value = null)
    {
        return new SelectEdit($value);
    }

    /**
     * 请求地址
     * @param $action
     * @return $this
     */
    public function action($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * 宽度
     * @param $width
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * 编辑对应的字段名称
     *
     * @param $filed
     * @return $this
     */
    public function field($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * 编辑后是否进行表格汇总更新
     *
     * @param $showSummary
     * @return $this
     */
    public function showSummary($showSummary)
    {
        $this->showSummary = $showSummary;
        return $this;
    }

    /**
     * @param $options
     * @return $this
     */
    public function options($options)
    {
        if ($options instanceof \Closure) {
            $this->options = call_user_func($options);
        } else {
            $this->options = $options;
        }

        return $this;
    }


}
