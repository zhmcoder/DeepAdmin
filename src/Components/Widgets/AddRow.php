<?php

namespace Andruby\DeepAdmin\Components\Widgets;

use Andruby\DeepAdmin\Components\Component;

class AddRow extends Component
{
    protected $componentName = "AddRow";
    protected $data;
    protected $is_title;
    protected $columns;
    protected $style = 'width:80%';

    protected $show_index;
    protected $index_prefix;
    protected $index_after;
    protected $index_width;

    protected $min_num = 1;
    protected $max_num = 999;

    /**
     * 1、阿拉伯数字
     * 2、汉字
     *
     * @var int
     */
    protected $index_model = 1;

    public static function make()
    {
        return new AddRow();
    }

    public function is_title($is_title = true)
    {
        $this->is_title = $is_title;
        return $this;
    }

    public function add_column(Column $column)
    {
        $this->columns[] = $column;
        return $this;
    }

    public function columns(array $column)
    {
        $this->columns = $column;
        return $this;
    }

    public function style($style)
    {
        $this->style = $style;
        return $this;
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

    public function show_index($show_index = true)
    {
        $this->show_index = $show_index;
        return $this;
    }

    public function index_prefix($index_prefix)
    {
        $this->index_prefix = $index_prefix;
        return $this;
    }

    public function index_after($index_after)
    {
        $this->index_after = $index_after;
        return $this;
    }

    public function index_width($index_width)
    {
        $this->index_width = $index_width;
        return $this;
    }

    public function min_num($min_num)
    {
        $this->min_num = $min_num;
        return $this;
    }

    public function max_num($max_num)
    {
        $this->max_num = $max_num;
        return $this;
    }

    public function index_model($index_model)
    {
        $this->index_model = $index_model;
        return $this;
    }

}
