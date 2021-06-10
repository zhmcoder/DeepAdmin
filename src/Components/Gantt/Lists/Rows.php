<?php

namespace Andruby\DeepAdmin\Components\Gantt\Lists;

class Rows
{
    /**
     * @var {string} required - row identifier
     */
    protected $id;
    /**
     * @var {string:GSTCID} - parent row identifier as GSTCID
     */
    protected $parentId;
    protected $classNames;// {string[] | function} - additional row classes you want to add as array of strings or a function that will return array of strings ({row, vido})=>['my-item-class']
    protected $style;// {object} - css style as object (for example {background:'red'})
    protected $expanded;// {boolean | undefined} - if row has children, it can be expanded (children visible) or collapsed (children hidden)
    protected $height;// {number} - row height in pixels
}
