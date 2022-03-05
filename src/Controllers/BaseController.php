<?php

namespace Andruby\DeepAdmin\Controllers;

use Andruby\DeepAdmin\Controllers\AdminController;
use Andruby\DeepAdmin\Controllers\HasResourceActions;
use Andruby\DeepAdmin\Form;

abstract class BaseController extends AdminController
{

    use HasResourceActions;

    protected $old_data = [];
    protected $is_edit = false;


    public function is_edit(Form $form)
    {
        return $this->is_edit;
    }

    protected function form($is_edit = false)
    {
        $this->is_edit = $is_edit;
        return $this->do_form($is_edit);
    }

    protected abstract function do_form($is_edit);
}
