<?php

namespace Andruby\DeepAdmin\Components\widgets;

use SmallRuralDog\Admin\Components\Component;

class AddRow extends Component
{
    protected $componentName = "AddRow";
    protected $data;
    protected $is_title;
    protected $columns;
    protected $style = 'width:80%';

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

}
