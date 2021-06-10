<?php

namespace Andruby\DeepAdmin\Components\Gantt;

class CalendarFormatArgument
{
//ChartCalendarFormatArgument is an object with these properties:

    protected $timeStart;// {dayjs} readonly - it's a dayjs object and it means where this calendar cell left side is located - for day it is a start of day
    protected $timeEnd;// {dayjs} readonly - dayjs object that tells where in time is the right side of current calendar cell, for day period it's end of day
    protected $vido;// {Vido} - library that we can use to generate lit-html result, get some state or call do something else
//TIP To determine in which cell we are - what date it is? we can simply format timeStart property to more user friendly format like timeStart.format('YYYY-MM-DD') will result in something like 2020-01-01
}
