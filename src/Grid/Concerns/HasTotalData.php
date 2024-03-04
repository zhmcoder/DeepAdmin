<?php

namespace Andruby\DeepAdmin\Grid\Concerns;

trait HasTotalData
{
    protected $totalData;

    /**
     * @param $totalData
     * @return $this
     */
    public function setTotalData($totalData)
    {
        $this->totalData = $totalData;

        return $this;
    }

    /**
     * @return $this
     */
    public function getTotalData()
    {
        return $this->totalData;
    }
}
