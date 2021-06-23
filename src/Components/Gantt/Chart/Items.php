<?php

namespace Andruby\DeepAdmin\Components\Gantt\Chart;

use SmallRuralDog\Admin\Traits\AdminJsonBuilder;

class Items extends AdminJsonBuilder
{
//Item
    protected $id;// {string:GSTCID} required - item id wrapped by GSTCID function

    protected $rowId;// {string:GSTCID} required - in which row this item should appear (as GSTCID)

    protected $label;// {string | function} required - item label, if function you could return lit-html template ({item, vido})=>vido.html`<div class="my-item">my item</div>`

    protected $time;// {object} required - item time configuration (below)

    protected $classNames;// {string[] | function} - array of css classes you want to add to current item, function should return an array of classNames too ({item, vido})=>['my-item-class']

    protected $isHTML;// {boolean} - you can pass label as simple html string like <div>item</div> if you are using lit-html template then this value should be set to false

    protected $style;// {object} - you can define styling for an item like {background:'red'}

    protected $linkedWith;// {string[]:GSTCID} array of other items that are linked, when you resize / move one other will follow

    protected $height;// {number} - item height in pixels (if not specified default will be used)

    protected $top;// {number} - top offset inside row (if not specified default will be used)

    protected $gap;// {object:Gap} - top and bottom item gap (if not specified default will be used)

    protected $minWidth;// {number} - minimal display width (if not specified default will be used)

    protected $progress=13;
//Item time
//To generate start and end time you should use GSTC.api.date().valueOf() method to generate it (see examples below). GSTC.api.date is dayjs function so you can use all of it's functionality.


    protected $start;// {number} - start time in milliseconds
    protected $end;// {number} - end time in milliseconds

    public static function make()
    {
        return (new Items())->is_filter_null(true);
    }

    /**
     * @param $start 甘特图块的开始时间，单位毫秒
     * @return $this
     */
    public function start($start)
    {
        $this->time['start'] = $start;
        return $this;
    }

    /**
     * @param $end 甘特图块的结束时间，单位毫秒
     * @return $this
     */
    public function end($end)
    {
        $this->time['end'] = $end;
        return $this;
    }

    public function id($id)
    {
        $this->id = $id;
        return $this;
    }
    public function getId()
    {
        return $this->id;
    }


    public function label($label)
    {
        $this->label = $label;
        return $this;
    }

    public function rowId($rowId)
    {
        $this->rowId = $rowId;
        return $this;
    }

    public function style(array $style)
    {
        $this->style = $style;
        return $this;
    }

    public function isHTML($isHTML)
    {
        $this->isHTML = $isHTML;
        return $this;
    }
}
