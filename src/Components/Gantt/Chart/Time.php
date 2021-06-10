<?php

namespace Andruby\DeepAdmin\Components\Gantt;

class Time
{
//Options
//from and to should be generated from GSTC.api.date('...').valueOf() function. If from and to are not specified GSTC will calculate those values basing on items time.

    protected $from;// {number} - from what point in time the chart should display dates (in milliseconds)
    protected $to;// {number} - where chart should stop displaying dates (in milliseconds)
    protected $fromDate;// {dayjs} readonly - from dayjs date instead of millisecond
    protected $toDate;// {dayjs} readonly - to dayjs date instead of millisecond
    protected $zoom;// {number} - current time zoom
    protected $leftGlobal;// {number} readonly - where we are currently in time from the left side in milliseconds - when you scroll this value will be updated
    protected $leftGlobalDate;// {dayjs} readonly - same as above but as dayjs date instead of milliseconds
    protected $rightGlobal;// {number} readonly - same as leftGlobal but for the right side of the chart
    protected $rightGlobalDate;// {dayjs} readonly - same as above but with dayjs object instead of milliseconds
    protected $onLevelDates;// {function[]} - array of functions that you can use to take control of dates that are displayed in calendar (see notice below)
    protected $onCurrentViewLevelDates;// {function[]} - same as above but limited to currently displayed dates - fired when horizontal scroll is moved (see notice below)
    protected $onDate;// {function[]} - array of functions that will be triggered when new calendar date is created (see notice below)
    protected $calculatedZoomMode;// {boolean} - if true GSTC will calculate zoom basing on from and to values to fill out whole space horizontally for example to show only one month inside current view and change months programmatically
//[NOTICE] onLevelDates, onCurrentViewLevelDates and onDate
//onLevelDates and onCurrentViewLevelDates takes one object as argument { dates, format, level, levelIndex } and should return original or modified dates. onDate takes one object argument { dates, format, level, levelIndex } with date instead of dates and should return original or modified date.
}
