<?php


namespace Andruby\DeepAdmin\Components\Form;

use Andruby\DeepAdmin\Components\Component;

class Path extends Component
{
    protected $componentName = "Path";

    /**
     * @var string
     */
    protected $label;
    protected $disabled = false;

    /**
     * 原生 name 属性
     * @var string
     */
    protected $name;

    protected $title;

    static public function make($value, $title)
    {
        return (new Path($value))->label($value)->title($title);
    }

    /**
     * Path 的 value
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
     * 原生 name 属性
     * @param string $name
     * @return $this
     */
    public function name(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function title(string $title)
    {
        $this->title = $title;
        return $this;
    }

}
