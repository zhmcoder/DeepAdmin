<?php

namespace Andruby\DeepAdmin\Components\Gantt;

use Illuminate\Support\Str;
use Andruby\DeepAdmin\Components\Component;

class GSTC extends Component
{
    protected $componentName = "GSTC";

    protected $innerHeight;// {number} - chart height in pixels (headerHeigh + innerHeight = total height)
    protected $headerHeight;// {number} - height of header and calendar in pixels
    protected $lists;// {object} - list configuration
    protected $chart;// {object} - chart configuration
    protected $locale;// {object} - locale configuration
    protected $slots;// {object} - slots to modify html content and functionality you should start here
    protected $actions;// {object} - actions can operate directly on HTMLElements and can be used to add some event listener or inject/modify some html of the component
    protected $scroll;// {object} - scroll
    protected $components;// {object} - object that holds components used inside GSTC - you can replace any component you want
    protected $plugins;// {array} - array of plugins that needs to be initialized before GSTC
    protected $plugin;// {object} - this is a container for plugins to store some data
    protected $merge;// {function} - alternative merge function merge(target: object, source: object): object
    protected $Promise;// {Promise} - alternative promise implementation
    protected $utcMode;// {boolean} - dayjs UTC mode on / off

    protected $licenseKey;

    public function __construct()
    {

    }

    public static function make()
    {
        return new GSTC();
    }

    public function licenseKey($licenseKey)
    {
        $this->licenseKey = $licenseKey;
        return $this;
    }

    public function innerHeight($innerHeight)
    {
        $this->innerHeight = $innerHeight;
        return $this;
    }

    public function headerHeight($headerHeight)
    {
        $this->headerHeight = $headerHeight;
        return $this;
    }

    public function lists(Lists $lists)
    {
        $this->lists = $lists;
        return $this;
    }

    public function chart(Chart $chart)
    {
        $this->chart = $chart;
        return $this;
    }
}
