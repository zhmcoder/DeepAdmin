<?php

namespace Andruby\DeepAdmin\Components\Gantt\Chart;

use Andruby\DeepAdmin\Traits\AdminJsonBuilder;

class CalendarLevel extends AdminJsonBuilder
{
//ChartCalendarLevelFormat
//ChartCalendarLevel is an array with formats (ChartCalendarLevelFormat) that are displayed inside calendar for specified chart.time.zoom property.

//ChartCalendarLevelFormat options
    protected $zoomTo;// {number} required - zoomTo means that this format will be used when config.chart.time.zoom will be less than or equal to this number
    protected $period;// {string} required - time period we want to display like day, month, year etc
    protected $periodIncrement;// {number} - if we want show for example 10 days as a one period / one calendar block / one grid block we can set period as day and this value as 10
    protected $main;// {boolean} - main means we want to draw a grid based on this format configuration and calculate the times, in the current config.chart.time.zoom there should be just one main format for grid (see below)
    protected $classNames;// {string[]} - array of strings - additional classes that needs to be added when this format is active
    protected $format;// {function} - function with one object ChartCalendarFormatArgument (described below) as an argument that should return string or lit-html template to display one calendar cell, this function will be fired for each calendar cell with argument described below
}
