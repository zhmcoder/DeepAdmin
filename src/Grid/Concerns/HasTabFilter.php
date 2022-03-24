<?php

namespace Andruby\DeepAdmin\Grid\Concerns;

use Andruby\DeepAdmin\Components\Form\Radio;
use Andruby\DeepAdmin\Grid\Column;
use Andruby\DeepAdmin\Grid\Model;
use Andruby\DeepAdmin\Grid\Tools\TabFilter;

/**
 * @method  Model model()
 */
trait HasTabFilter
{
    /**
     * @property Column $columns
     * @var TabFilter
     */
    public $tabFilter;
    private $or = false;
    private $operator = '=';
    private $position = 'left';

    public function tabFilter()
    {
        $this->tabFilter = new TabFilter();
        $this->tabFilter->defaultValue = '';
        $this->tabFilter->position = $this->position;
        return $this;
    }

    /**
     * @param string $filterKey 过滤的数据库字段
     * @return $this
     */
    public function tabFilterKey(string $filterKey)
    {
        if ($this->tabFilter == null) {
            $this->tabFilter = new TabFilter();
        }
        $this->tabFilter->filterKey = $filterKey;
        return $this;
    }

    public function tabQuickOptions($options = [], $show_all = true)
    {
        if ($this->tabFilter == null) {
            $this->tabFilter = new TabFilter();
        }
        $this->tabFilter->options = $options;
        if ($show_all) {
            array_unshift($this->tabFilter->options, Radio::make('', '全部'));
        }
        return $this;
    }

    public function tabDefaultValue($defaultValue)
    {
        if ($this->tabFilter == null) {
            $this->tabFilter = new TabFilter();
        }
        $this->tabFilter->defaultValue = $defaultValue;
        return $this;
    }

    public function tabOr($or = true)
    {
        $this->or = $or;
        return $this;
    }

    public function tabOperator($operator = '=')
    {
        $this->operator = $operator;
        return $this;
    }

    public function tabPosition($position)
    {
        $this->tabFilter->position = $position;
        return $this;
    }

    protected function applyTabFilter()
    {
        if (!$this->tabFilter) {
            return;
        }
        $query = request()->get($this->tabFilter->filterKey);
        if ($query === 'NULL') {
            $query = null;
        }
        if ($query == '' || $query == null) {
            return;
        }
        if (strpos($this->tabFilter->filterKey, 'default_') !== false) {
            return;
        }
        $this->addWhereBasicBinding($this->tabFilter->filterKey, $this->or, $this->operator, $query);
    }
}
