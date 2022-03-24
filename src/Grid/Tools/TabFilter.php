<?php

namespace Andruby\DeepAdmin\Grid\Tools;

use JsonSerializable;

class TabFilter implements JsonSerializable
{
    public $filterKey;
    public $options;
    public $defaultValue;
    public $position;

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'filterKey' => $this->filterKey,
            'defaultValue' => $this->defaultValue,
            'position' => $this->position,
            'options' => $this->options
        ];
    }
}
