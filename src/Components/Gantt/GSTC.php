<?php

namespace Andruby\DeepAdmin\Components\Gantt;

use Illuminate\Support\Str;
use SmallRuralDog\Admin\Components\Component;

class GSTC extends Component
{
    protected $componentName = "GSTC";


    public function __construct()
    {

    }

    public static function make()
    {
        return new GSTC();
    }

}
