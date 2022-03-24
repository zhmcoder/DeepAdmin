<?php

namespace Andruby\DeepAdmin\Grid\Concerns;

use Andruby\DeepAdmin\Components\Form\Radio;
use Andruby\DeepAdmin\Grid\Column;
use Andruby\DeepAdmin\Grid\Model;
use Andruby\DeepAdmin\Grid\Tools\QuickFilter;

//<!--deep admin start-->

/**
 * @method  Model model()
 */
trait HasQuickFilter
{
    /**
     * @property Column $columns
     * @var QuickFilter
     */
    public $quickFilter;
    private $or = false;
    private $operator = '=';
    private $position = 'left';

    public function quickFilter()
    {
        $this->quickFilter = new QuickFilter();
        $this->quickFilter->defaultValue = '';
        $this->quickFilter->position = $this->position;
        return $this;
    }

    /**
     * @param string $filterKey 过滤的数据库字段
     * @return $this
     */
    public function filterKey(string $filterKey)
    {
        if ($this->quickFilter == null) {
            $this->quickFilter = new QuickFilter();
        }
        $this->quickFilter->filterKey = $filterKey;
        return $this;
    }

    public function quickOptions($options = [], $show_all = true)
    {
        if ($this->quickFilter == null) {
            $this->quickFilter = new QuickFilter();
        }
        $this->quickFilter->options = $options;
        if ($show_all) {
            array_unshift($this->quickFilter->options, Radio::make('', '全部'));
        }
        return $this;
    }

    public function defaultValue($defaultValue)
    {
        if ($this->quickFilter == null) {
            $this->quickFilter = new QuickFilter();
        }
        $this->quickFilter->defaultValue = $defaultValue;
        return $this;
    }

    public function or($or = true)
    {
        $this->or = $or;
        return $this;
    }

    public function operator($operator = '=')
    {
        $this->operator = $operator;
        return $this;
    }

    public function position($position)
    {
        $this->tabFilter->position = $position;
        return $this;
    }

    /**
     * Apply the search query to the query.
     *
     * @return mixed|void
     */
    protected function applyQuickFilter()
    {
        if (!$this->quickFilter) {
            return;
        }
        $query = request()->get($this->quickFilter->filterKey);
        if ($query === 'NULL') {
            $query = null;
        }
        if ($query == '' || $query == null) {
            return;
        }
        if (strpos($this->quickFilter->filterKey, 'default_') !== false) {
            return;
        }
        $this->addWhereBasicBinding($this->quickFilter->filterKey, $this->or, $this->operator, $query);
    }
}
//<!--deep admin end-->
