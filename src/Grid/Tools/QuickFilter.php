<?php

namespace Andruby\DeepAdmin\Grid\Tools;

use JsonSerializable;

//<!--deep admin start-->
class QuickFilter implements JsonSerializable
{
    public $filterKey;
    public $options;
    public $defaultValue;
    public $position;
    public $removeUrl;

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'filterKey' => $this->filterKey,
            'defaultValue' => $this->defaultValue,
            'position' => $this->position,
            'options' => $this->options,
            'removeUrl' => $this->removeUrl,
        ];
    }
}
//<!--deep admin end-->
