<?php

namespace Andruby\DeepAdmin\Grid\Actions;

use Andruby\DeepAdmin\Actions\BaseRowAction;

class DeleteDialogAction extends BaseRowAction
{
    protected $componentName = "DeleteDialogAction";

    protected $type = "text";

    protected $content = "删除";

    protected $message = "确定要删除此内容？";

    protected $title = '删除提示';

    protected $cancel = '取消';

    protected $confirm = '确认';

    /**
     * title 名称
     * @param mixed $title
     * @return $this
     */
    public function title($title): DeleteDialogAction
    {
        $this->title = $title;
        return $this;
    }

    /**
     * cancel 名称
     * @param mixed $cancel
     * @return $this
     */
    public function cancel($cancel): DeleteDialogAction
    {
        $this->cancel = $cancel;
        return $this;
    }

    /**
     * confirm 名称
     * @param mixed $confirm
     * @return $this
     */
    public function confirm($confirm): DeleteDialogAction
    {
        $this->confirm = $confirm;
        return $this;
    }
}
