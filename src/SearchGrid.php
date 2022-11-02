<?php

namespace Andruby\DeepAdmin;

class SearchGrid extends Grid
{
    /**
     * 组件名称
     * @var string
     */
    protected $componentName = 'SearchGrid';

    protected bool $isMultiple = false; // 是否多选（false单选、true多选）

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
