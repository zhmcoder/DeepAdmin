<?php


namespace Andruby\DeepAdmin\Grid\Tools;


use Andruby\DeepAdmin\Actions\BaseAction;
use Andruby\DeepAdmin\Components\Attrs\Button;

class CreateButton extends BaseAction
{
    use Button;

    protected $componentName = "GridCreateButton";

    protected $isDialog = false;

    /**
     * @param bool $isDialog
     * @return CreateButton
     */
    public function isDialog(bool $isDialog)
    {
        $this->isDialog = $isDialog;
        return $this;
    }





}
