<?php

namespace Andruby\DeepAdmin\Components\Gantt\Lists\Columns;

use SmallRuralDog\Admin\Traits\AdminJsonBuilder;

class Header extends  AdminJsonBuilder
{

//Column data header is an object with one of these properties:

    protected $content;// {string | lit-html} - Label for this header it could be simple string or lit-html template or

//    protected $html;// {string} - html string (be careful with this)

    /**
     * @param $content 列的标题
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    public static function make()
    {
        return new Header();
    }
}
