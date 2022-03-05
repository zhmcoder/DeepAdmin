<?php

namespace Andruby\DeepAdmin\Components\Grid;

use Andruby\DeepAdmin\Components\Component;
use Andruby\DeepAdmin\Components\Grid\Boole;


class SortEdit extends Component
{
    protected $componentName = "SortEdit";
    protected $action = null;
    protected $width = 60;
    protected $field = 'sort';
    protected $showSummary = false;

    public static function make($value = null)
    {
        return new SortEdit($value);
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


}
