<?php


namespace Andruby\DeepAdmin\Grid\Concerns;


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
     */
    protected $filter;
    protected $leftFilter;
    protected $rightFilter;


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
}
