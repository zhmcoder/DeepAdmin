<?php

namespace Andruby\DeepAdmin;

class SearchGrid extends Grid
{
    /**
     * 组件名称
     * @var string
     */
    protected $componentName = 'SearchGrid';

    /**
     * 单选 || 多选
     * @param bool $isMultiple
     * @return $this
     */
    public function isMultiple(bool $isMultiple = true): SearchGrid
    {
        $this->isMultiple = $isMultiple;
        return $this;
    }
}
