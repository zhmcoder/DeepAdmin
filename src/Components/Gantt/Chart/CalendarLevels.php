<?php

namespace Andruby\DeepAdmin\Components\Gantt;

class CalendarLevels
{
//Options
    protected $height;// {number} - height in pixels
    protected $top;// {number} - offset top inside row in pixels
    protected $minWidth;// {number} - minimal display width in pixels
    protected $cutIcons;// {object:CutIcons} - icons displayed when item is not completely rendered in current view (below)
    protected $gap;// {object:Gap} - vertical gap inside row (below)
//Cut icons
    protected $left;// {string} - svg code for left icon
    protected $right;// {string} - svg code for right icon
//                                                           Gap
    protected $gapTop;// {number} - top offset inside row in pixels
    protected $gapBottom;// {number} - bottom offset inside row in pixels
}
