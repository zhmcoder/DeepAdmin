<?php

namespace Andruby\DeepAdmin\Grid\Concerns;

trait HasTotalData
{
    protected $totalData;
    protected $totalDataCompute;

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

    public function setTotalDataCompute($totalDataCompute)
    {
        $this->totalDataCompute = $totalDataCompute;

        return $this;
    }

    public function getTotalDataCompute()
    {
        return $this->totalDataCompute;
    }
}
