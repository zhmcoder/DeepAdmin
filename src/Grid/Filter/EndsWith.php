<?php

namespace Andruby\DeepAdmin\Grid\Filter;

class EndsWith extends Like
{
    protected $exprFormat = '%{value}';
}
