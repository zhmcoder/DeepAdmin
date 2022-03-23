<?php

namespace Andruby\DeepAdmin\Components\Form;

class SelectMenu extends Select
{
    protected $componentName = "SelectMenu";

    protected $isMore = false; // 更多是否开启
    protected $isRow = true; // 是否一行展示

    public static function make($value = null)
    {
        return new SelectMenu($value);
    }

    public function isMore($isMore = true)
    {
        $this->isMore = $isMore;
        return $this;
    }

    public function isRow($isRow = true)
    {
        $this->isRow = $isRow;
        return $this;
    }

}
