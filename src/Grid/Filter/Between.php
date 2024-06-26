<?php

namespace Andruby\DeepAdmin\Grid\Filter;

use Illuminate\Support\Arr;

class Between extends AbstractFilter
{

    // deep-admin start
    protected $timestamp = false;
    protected $datetime = false;
    protected $date = false;

    public function datetime()
    {
        $this->datetime = true;
        return $this;
    }

    public function date()
    {
        $this->date = true;
        return $this;
    }

    public function time()
    {
        $this->timestamp = true;
        return $this;
    }
    // deep-admin end

    /**
     * Get condition of this filter.
     *
     * @param array $inputs
     *
     * @return mixed
     */
    public function condition($inputs)
    {
        if (!Arr::has($inputs, $this->column)) {
            return;
        }

        $this->value = Arr::get($inputs, $this->column);

        if (!is_array($this->value)) {
            return;
        }

        $value = array_filter($this->value, function ($val) {
            return $val !== '';
        });

        if (empty($value)) {
            return;
        }

        // deep-admin start
        if (!isset($value[0])) {
            return $this->buildCondition($this->column, '>=', $value[0] . ' 00:00:00');
        }

        if (!isset($value[1])) {
            return $this->buildCondition($this->column, '>=', $value[1] . ' 59:59:59');
        }

        $this->query = 'whereBetween';

        if ($this->timestamp) {
            $this->value[0] = strtotime($this->value[0] . ' 00:00:00');
            $this->value[1] = strtotime($this->value[1] . ' 23:59:59');
        }

        if ($this->datetime) {
            if (strlen($this->value[0]) == 10) {
                $this->value[0] = $this->value[0] . ' 00:00:00';
            }
            if (strlen($this->value[1]) == 10) {
                $this->value[1] = $this->value[1] . ' 23:59:59';
            }
        }

        if ($this->date) {
            $this->value[0] = $this->value[0];
            $this->value[1] = $this->value[1];
        }
        // deep-admin end

        return $this->buildCondition($this->column, $this->value);
    }


}
