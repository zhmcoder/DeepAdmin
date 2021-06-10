<?php

namespace Andruby\DeepAdmin\Components\Gantt;

class Chart
{
//Options
    protected $item;// {object} - default options for item - if not specified inside items configuration
    protected $items;// {object} - items configuration
    protected $time;// {object} - chart time configuration
    protected $calendarLevels;// {array} - calendar levels
    protected $grid;// {object} - chart grid
    protected $spacing;// number - space between items in pixels
}
