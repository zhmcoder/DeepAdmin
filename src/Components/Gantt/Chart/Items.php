<?php

namespace Andruby\DeepAdmin\Components\Gantt;

class Items
{
//Item
    protected $id;// {string:GSTCID} required - item id wrapped by GSTCID function

    protected $idrowId;// {string:GSTCID} required - in which row this item should appear (as GSTCID)

    protected $idlabel;// {string | function} required - item label, if function you could return lit-html template ({item, vido})=>vido.html`<div class="my-item">my item</div>`

    protected $idtime;// {object} required - item time configuration (below)

    protected $idclassNames;// {string[] | function} - array of css classes you want to add to current item, function should return an array of classNames too ({item, vido})=>['my-item-class']

    protected $idisHTML;// {boolean} - you can pass label as simple html string like <div>item</div> if you are using lit-html template then this value should be set to false

    protected $idstyle;// {object} - you can define styling for an item like {background:'red'}

    protected $idlinkedWith;// {string[]:GSTCID} array of other items that are linked, when you resize / move one other will follow

    protected $idheight;// {number} - item height in pixels (if not specified default will be used)

    protected $idtop;// {number} - top offset inside row (if not specified default will be used)

    protected $idgap;// {object:Gap} - top and bottom item gap (if not specified default will be used)

    protected $idminWidth;// {number} - minimal display width (if not specified default will be used)

//Item time
//To generate start and end time you should use GSTC.api.date().valueOf() method to generate it (see examples below). GSTC.api.date is dayjs function so you can use all of it's functionality.

    protected $idstart;// {number} - start time in milliseconds
    protected $idend;// {number} - end time in milliseconds
}
