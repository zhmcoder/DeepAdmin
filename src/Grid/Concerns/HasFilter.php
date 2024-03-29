<?php


namespace Andruby\DeepAdmin\Grid\Concerns;


use Andruby\DeepAdmin\Grid\TreeFilter;
use Closure;
use Andruby\DeepAdmin\Grid\Filter;
use Andruby\DeepAdmin\Grid\LeftFilter;
use Andruby\DeepAdmin\Grid\RightFilter;

trait HasFilter
{
    /**
     * @var Filter
     * @var LeftFilter
     * @var RightFilter
     * @var TreeFilter
     */
    protected $filter;
    protected $leftFilter;
    protected $rightFilter;
    protected $treeFilter;


    public function applyFilter($toArray = true)
    {

        return $this->filter->execute($toArray);
    }

    public function filter(Closure $callback)
    {
        call_user_func($callback, $this->filter);
    }

    public function leftFilter(Closure $callback)
    {
        call_user_func($callback, $this->leftFilter);
    }

    public function rightFilter(Closure $callback)
    {
        call_user_func($callback, $this->rightFilter);
    }

    public function treeFilter(Closure $callback)
    {
        call_user_func($callback, $this->treeFilter);
    }
}
