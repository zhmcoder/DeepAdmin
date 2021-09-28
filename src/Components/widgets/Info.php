<?php

namespace Andruby\DeepAdmin\Components\widgets;

use SmallRuralDog\Admin\Components\Component;

class Info extends Component
{
    protected $componentName = "Info";
    protected $info_list;

    public static function make()
    {
        return new Info();
    }

    public function info_list($info_list)
    {
        $this->info_list = $info_list;
        return $this;
    }

}