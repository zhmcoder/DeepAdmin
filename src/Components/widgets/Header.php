<?php

namespace Andruby\DeepAdmin\Components\widgets;

use SmallRuralDog\Admin\Components\Component;

class Header extends Component
{

    protected $label;
    protected $headers;

    public static function make()
    {
        return new AddRow();
    }

    public function is_title($is_title)
    {
        $this->is_title = $is_title;
        return $this;
    }


}
