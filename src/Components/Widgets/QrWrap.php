<?php

namespace Andruby\DeepAdmin\Components\Widgets;

use Andruby\DeepAdmin\Components\Component;

class QrWrap extends Component
{
    protected $componentName = 'QrWrap';
    protected $href = '';
    protected $src = '';

    public static function make()
    {
        return new QrWrap();
    }

    /**
     * 链接地址
     */
    public function setHref($href = '')
    {
        $this->href = $href;
    }

    /**
     * 图片地址
     */
    public function setSrc($src = '')
    {
        $this->src = $src;
    }
}
