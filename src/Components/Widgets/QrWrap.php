<?php

namespace Andruby\DeepAdmin\Components\Widgets;

use Andruby\DeepAdmin\Components\Component;

class QrWrap extends Component
{
    protected $componentName = 'QrWrap';
    protected $href = '';
    protected $src = null;
    protected $down = null;
    protected $title = null;
    protected $help = null;

    public static function make()
    {
        return new QrWrap();
    }

    /**
     * 链接地址
     */
    public function setHref($href = ''): QrWrap
    {
        $this->href = $href;
        return $this;
    }

    /**
     * 图片地址
     */
    public function setSrc($src = null): QrWrap
    {
        $this->src = $src;
        return $this;
    }

    /**
     * 图片下载地址
     */
    public function setDown($down = null): QrWrap
    {
        $this->down = $down;
        return $this;
    }

    public function setTitle($title = ''): QrWrap
    {
        $this->title = $title;
        return $this;
    }

    public function setHelp($help = ''): QrWrap
    {
        $this->help = $help;
        return $this;
    }
}
