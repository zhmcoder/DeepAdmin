<?php


namespace Andruby\DeepAdmin\Components\Attrs;


use Andruby\DeepAdmin\Traits\AdminJsonBuilder;

class TransferData extends AdminJsonBuilder
{

    protected $key;
    protected $label;
    protected $disabled = false;


    static public function make($key, $label, $disabled = false)
    {
        return new TransferData($key, $label, $disabled);
    }

    /**
     * TransferData constructor.
     * @param $key
     * @param $label
     * @param bool $disabled
     */
    public function __construct($key, $label, $disabled)
    {
        $this->key = $key;
        $this->label = $label;
        $this->disabled = $disabled;
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key => $val) {
            $data[$key] = $val;
        }
        return $data;
    }
}
