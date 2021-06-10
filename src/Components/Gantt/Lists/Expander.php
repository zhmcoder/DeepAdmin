<?php

namespace Andruby\DeepAdmin\Components\Gantt\Lists;

class Expander
{
//Options
    protected $padding;// {number} - left padding size in pixels
    protected $size;// {number} - size in pixels (width and height)
    protected $icon;// {object} - with width and height properties in pixels {numbers} like {width: 16, height: 16}
    protected $icons;// {object} - expander icons configuration (below)
//Column data header expander icons
    protected $child;// {string} - svg code for non expandable child element
    protected $open;// {string} - svg code for open
    protected $closed;// {string} - svg code for closed
}
