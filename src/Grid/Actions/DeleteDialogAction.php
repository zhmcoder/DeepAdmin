<?php

namespace Andruby\DeepAdmin\Grid\Actions;

use Andruby\DeepAdmin\Actions\BaseRowAction;

class DeleteDialogAction extends BaseRowAction
{
    protected $componentName = "DeleteDialogAction";

    protected $type = "text";

    protected $content = "删除";

    protected $message = "确定要删除此内容？";
}
