<?php

namespace Andruby\DeepAdmin\Components\Gantt;

use Andruby\DeepAdmin\Components\Gantt\Lists\Columns;
use SmallRuralDog\Admin\Traits\AdminJsonBuilder;

class Lists extends AdminJsonBuilder
{
    protected $row; //{object} - default row settings
    protected $rows; //{object} - rows configuration
    protected $columns; //{object} - columns configuration
    protected $sort; //{object} - sorting
    protected $expander; //{object} - expander
    protected $toggle; //{object} - list toggle - hide / show list
    protected $rowHeight; //{number} - default row height in pixels - this option can be set individually for each row

    private $rows_data = [];

    public function columns(Columns $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function addRows($rows)
    {
        $this->rows_data[] = $rows;
        return $this;
    }

    public static function make()
    {
        return new Lists();
    }

    public function jsonSerialize()
    {
        $data = [
            'columns' => $this->columns,
            'row' => $this->row,
            'rows' => $this->rows
        ];

        $row=[];

        foreach ($this->rows_data as $item){
//            $row_item[$item['']]
        }

        $data['rows'] =$row;

        return $data;
    }
}
