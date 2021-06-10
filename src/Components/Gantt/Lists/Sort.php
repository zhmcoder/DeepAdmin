<?php

namespace Andruby\DeepAdmin\Components\Gantt\Lists;

class Sort
{
//Options
    protected $activeColumnId;// {string | null} readonly - on which column sorting is activated?
    protected $asc;// {boolean} - ascending ?
    protected $compare;// {function | null} - function with column argument that will return compare function with column argument
    protected $icons;// {object:SortIcons} - icons to indicate sort direction (below)
//Sort icons
    protected $up;// {string} - svg code for up
    protected $down;//{string} - svg code for down
}
