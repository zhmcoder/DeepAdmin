<?php

namespace Andruby\DeepAdmin\Components\Gantt\Lists\Columns;

class Data
{
    //Column data configuration
    protected $id;// {string:GSTCID} required - id of the column
    protected $data;// {string | function} required - for string it is a property name that should exists inside row configuration and will display corresponding value, if data is a function it will be executed with {row, vido} object as argument - that function should return a string or lit-html template
    protected $isHTML;// {boolean} - if set to true data option will be rendered as HTML so be careful and do not let user to inject anything unsafe!
    protected $width;// {number} required - width of the column in pixels
    protected $header;// {object} required - column header configuration described below
    protected $sortable;// {string | function} - if this is a string then should contain name of the row field needed to sort rows, if this is a function then should return value from row that will be compared with another row value ({row, vido})=>any
    protected $expander;// {boolean} - should this column contain expander to collapse or expand children? expander options are described below
    //Column data header
    //Column data header is an object with one of these properties:
    protected $content;// {string | lit-html} - Label for this header it could be simple string or lit-html template or
    protected $html;// {string} - html string (be careful with this)


}
