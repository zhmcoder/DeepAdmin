<?php

namespace Andruby\DeepAdmin\Components\Gantt\Lists;

use Andruby\DeepAdmin\Components\Gantt\Lists\Columns\Data;
use SmallRuralDog\Admin\Traits\AdminJsonBuilder;

class Columns extends AdminJsonBuilder
{
    private $id_prefix = "gstcid-";

//Options
    protected $data = [];// {object} - columns data configuration
    protected $resizer;// {object} - resizer configuration
    protected $percent;// {number} - percentage width of all columns (0 - 100) if 0 list will disappear (from DOM)
    protected $minWidth;// {number} - default minimal width of the column in pixels

    public function addData(Data $data)
    {
        $this->data[] = $data;
        return $this;
    }

    public static function make()
    {
        return new Columns();
    }

    public function jsonSerialize()
    {
        $data = null;
        foreach ($this->data as $item) {
            $item = $item->jsonSerialize();
            $data[$this->id_prefix . $item['id']] = $item;
            $data[$this->id_prefix . $item['id']]['id'] = $this->id_prefix . $item['id'];
        }
        return ['data'=>$data];
    }
}
