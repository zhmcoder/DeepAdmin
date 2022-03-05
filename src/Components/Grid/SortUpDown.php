<?php

namespace Andruby\DeepAdmin\Components\Grid;

use Andruby\DeepAdmin\Components\Component;
use Andruby\DeepAdmin\Components\Grid\Boole;


class SortUpDown extends Component
{
    protected $componentName = "SortUpDown";
    private $value = null;
    private $sort_action;

    // public function __construct(int $value)
    // {
    //     $this->value = $value;
    // }

    public function setSortAction($setSortAction)
    {
        $this->setSortAction = $setSortAction;
        return $this;
    }

    public static function make($value = null)
    {
        return new SortUpDown($value);
    }


}
