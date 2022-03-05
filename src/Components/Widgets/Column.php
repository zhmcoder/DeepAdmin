<?php

namespace Andruby\DeepAdmin\Components\Widgets;

use Andruby\DeepAdmin\Components\Component;

class Column extends Component
{

    protected $label;
    protected $component;
    protected $prop;
    protected $width;
    protected $value;

    public static function make()
    {
        return new Column();
    }

    public function label($label)
    {
        $this->label = $label;
        return $this;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function component(Component $component)
    {
        $this->component = $component;
        return $this;
    }

    public function prop($prop)
    {
        $this->prop = $prop;
        return $this;
    }

    public function width($width)
    {
        $this->width = $width;
        return $this;
    }


}
