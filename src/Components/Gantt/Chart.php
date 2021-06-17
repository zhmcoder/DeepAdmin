<?php

namespace Andruby\DeepAdmin\Components\Gantt;

use SmallRuralDog\Admin\Traits\AdminJsonBuilder;

class Chart extends AdminJsonBuilder
{
//Options
    protected $item;// {object} - default options for item - if not specified inside items configuration
    protected $items;// {object} - items configuration
    protected $time;// {object} - chart time configuration
    protected $calendarLevels;// {array} - calendar levels
    protected $grid;// {object} - chart grid
    protected $spacing;// number - space between items in pixels

    public static function make()
    {
        return new Chart();
    }

    public function items(array $items)
    {
        foreach ($items as $item) {
            $this->items[$item['id']] = $item;
        }
        return $this;
    }
}