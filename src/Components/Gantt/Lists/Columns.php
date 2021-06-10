<?php

namespace Andruby\DeepAdmin\Components\Gantt\Lists;

use Andruby\DeepAdmin\Components\Gantt\Lists\Columns\Data;

class Columns
{
//Options
    protected $data;// {object} - columns data configuration
    protected $resizer;// {object} - resizer configuration
    protected $percent;// {number} - percentage width of all columns (0 - 100) if 0 list will disappear (from DOM)
    protected $minWidth;// {number} - default minimal width of the column in pixels

    public function data(Data $data)
    {
        $this->data = $data;
        return $this;
    }
}
