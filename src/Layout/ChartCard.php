<?php

namespace Andruby\DeepAdmin\Layout;

use Andruby\DeepAdmin\Components\Component;
use Andruby\DeepAdmin\Components\Form\Select;
use Andruby\DeepAdmin\Components\Widgets\Html;

class ChartCard extends Component
{

    protected $componentName = "ChartCard";

    protected $title = '';
    protected $filters = [];
    protected $filter = null;
    protected $chart;
    protected $data_url;
    protected $depend;
    protected $filterData = [];

    public function __construct()
    {

    }


    public static function make()
    {
        return new ChartCard();
    }

    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    public function addFilter(Select $select)
    {
        $this->filters[] = $select;
        if ($select->getRef()) {
            $this->filterData[$select->getRef()] = $select->getComponentValue();
        }
        return $this;
    }

//    public function filter(Select $select)
//    {
//        $this->filter = $select;
//        if ($select->getRef()) {
//            $this->filterData[$select->getRef()] = $select->getComponentValue();
//        }
//
//        return $this;
//    }

    public function chart($chart)
    {
        $this->chart = $chart;
        return $this;
    }

    public function data_url($data_url)
    {
        $this->data_url = $data_url;
        return $this;
    }


}
