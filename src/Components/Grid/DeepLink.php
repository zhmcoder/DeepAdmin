<?php

namespace Andruby\DeepAdmin\Components\Grid;

use Andruby\DeepAdmin\Components\GridComponent;

class DeepLink extends GridComponent
{
    protected $componentName = "Link";
    protected $type;
    protected $underline;
    protected $disabled = false;
    protected $href;
    protected $icon;
    protected $target = '_self';


    static public function make($value = null)
    {
        return new DeepLink($value);
    }

    /**
     * 类型   success/info/warning/danger
     * @param string|array $type
     * @param bool $random
     * @return $this
     */
    public function type($type = null, $random = true)
    {
        $type = empty($type) ? ['primary', 'success', 'info', 'warning', 'danger'] : $type;

        $this->type = [
            'data' => $type,
            'random' => $random
        ];

        return $this;
    }

    /**
     * @param bool $underline
     * @return $this
     */
    public function underline($underline = true)
    {
        $this->underline = $underline;
        return $this;
    }

    /**
     * @param bool $disabled
     * @return $this
     */
    public function disabled(bool $disabled = true)
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @param string $href
     * @return $this
     */
    public function href($href)
    {
        $this->href = $href;
        return $this;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * target _blank/_self/_parent/_top
     * @param string $target
     * @return $this
     */
    public function target($target)
    {
        $this->target = $target;
        return $this;
    }

}
