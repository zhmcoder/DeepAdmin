<?php

namespace Andruby\DeepAdmin\Components\Form;

use Andruby\DeepAdmin\Components\Component;

class RowMulti extends Component
{
    protected $componentName = "RowMulti";

    protected $component;
    protected $multiData;

    public static function make()
    {
        return new RowMulti();
    }

    public function addComponents(MultiItem $multiItem)
    {
        $this->multiData[$multiItem->getProp()] = $multiItem->getValue();
        $this->component[] = $multiItem;
        return $this;
    }

}
