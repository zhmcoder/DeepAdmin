<?php

namespace Andruby\DeepAdmin\Grid\Filter;

use Andruby\DeepAdmin\Components\Form\InputPhone;

class Phone extends AbstractFilter
{
    public function __construct($column, $label = '')
    {
        $this->column = $column;
        $this->label = $label != null ? $this->formatLabel($label) : null;
        $this->component = InputPhone::make();
    }
}
