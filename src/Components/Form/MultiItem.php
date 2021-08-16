<?php

namespace Andruby\DeepAdmin\Components\Form;

use SmallRuralDog\Admin\Components\Component;

class MultiItem extends Component
{
    protected $label;
    protected $labelStyle;
    protected $component;
    protected $prop;
    protected $width;
    protected $value;
    protected $afterLabel;
    protected $afterLabelStyle;

    public static function make()
    {
        return new MultiItem();
    }

    public function label($label)
    {
        $this->label = $label;
        return $this;
    }

    public function labelStyle($labelStyle)
    {
        $this->labelStyle = $labelStyle;
        return $this;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
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

    public function getProp()
    {
        return $this->prop;
    }

    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    public function afterLabel($afterLabel)
    {
        $this->afterLabel = $afterLabel;
        return $this;
    }

    public function afterLabelStyle($afterLabelStyle)
    {
        $this->afterLabelStyle = $afterLabelStyle;
        return $this;
    }

}
