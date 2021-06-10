<?php

namespace Andruby\DeepAdmin\Components\Gantt;

use Andruby\DeepAdmin\Components\Gantt\Lists\Columns;

class Lists
{
    protected $row; //{object} - default row settings
    protected $rows; //{object} - rows configuration
    protected $columns; //{object} - columns configuration
    protected $sort; //{object} - sorting
    protected $expander; //{object} - expander
    protected $toggle; //{object} - list toggle - hide / show list
    protected $rowHeight; //{number} - default row height in pixels - this option can be set individually for each row


    public function columns(Columns $columns)
    {
        $this->columns = $columns;
        return $this;
    }
}
