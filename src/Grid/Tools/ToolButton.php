<?php


namespace Andruby\DeepAdmin\Grid\Tools;


use Andruby\DeepAdmin\Actions\BaseAction;
use Andruby\DeepAdmin\Components\Attrs\Button;

class ToolButton extends BaseAction
{
    use Button;

    protected $uri;
    protected $componentName = "ToolButton";
    protected $handler;
    // deep-admin start
    protected $isFilterFormData;
    // deep-admin end

    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @param string $content 按钮内容
     * @return ToolButton
     */
    public static function make($content)
    {
        return new ToolButton($content);
    }

    /**
     * @param mixed $uri
     * @return $this
     */
    public function uri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @param string $handler 响应类型 request|route|link
     * @return $this
     */
    public function handler($handler)
    {
        $this->handler = $handler;
        return $this;
    }
    // deep-admin start
    public function isFilterFormData($isFilterFormData)
    {
        $this->isFilterFormData = $isFilterFormData;
        return $this;
    }
    // deep-admin end


}
