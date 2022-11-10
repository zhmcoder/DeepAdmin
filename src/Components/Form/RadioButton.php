<?php

namespace Andruby\DeepAdmin\Components\Form;

use Andruby\DeepAdmin\Components\Component;

class RadioButton extends Component
{
    protected $componentName = "RadioButton";

    /**
     * @var string
     */
    protected $label;
    protected $disabled = false;
    protected $border = false;
    protected $size;
    protected $name;
    protected $title;
    protected $img;

    static public function make($value, $title)
    {
        return (new RadioButton($value))->label($value)->title($title);
    }

    /**
     * Radio 的 value
     * @param  $label
     * @return $this
     */
    public function label($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * 是否禁用
     * @param bool $disabled
     * @return $this
     */
    public function disabled(bool $disabled)
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * 是否显示边框
     * @param bool $border
     * @return $this
     */
    public function border(bool $border = true)
    {
        $this->border = $border;
        return $this;
    }

    /**
     * Radio 的尺寸，仅在 border 为真时有效
     * @param string $size
     * @return $this
     */
    public function size(string $size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * 原生 name 属性
     * @param string $name
     * @return $this
     */
    public function name(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * title 标题
     * @param string $title
     * @return $this
     */
    public function title(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * img 图片
     * @param string $img
     * @return $this
     */
    public function img($img = '')
    {
        $this->img = $img;
        return $this;
    }
}
