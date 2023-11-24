<?php

namespace Andruby\DeepAdmin\Controllers;

use Andruby\DeepAdmin\Auth\Database\OperationLog;

use Andruby\DeepAdmin\Components\Form\Input;
use Andruby\DeepAdmin\Components\Grid\Avatar;
use Andruby\DeepAdmin\Components\Grid\Tag;
use Andruby\DeepAdmin\Form;
use Andruby\DeepAdmin\Grid;

class LogController extends AdminController
{
    protected function grid()
    {
        $grid = new Grid(new OperationLog());

        $grid->filter(function ($filter) {

            $filter->where(function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });
            }, '用户', 'name');

            $filter->like('method', '请求方式')->component(
                Input::make()->clearable()->style('width:150px;margin-left:5px;')
            );

            $filter->like('path', '路径')->component(
                Input::make()->clearable()->style('width:150px;margin-left:5px;')
            );

            $filter->like('ip', 'IP')->component(
                Input::make()->clearable()->style('width:150px;margin-left:5px;')
            );
        });

        $grid->column('id', "序号")->sortable(true)->align('center');
        $grid->column('user.avatar', '头像', 'user_id')->component(Avatar::make()->size('small'));
        $grid->column('user.name', '用户', 'user_id')->help("操作用户")->sortable();
        $grid->column('method', '请求方式')->align('center')->component(Tag::make()->type(['GET' => 'info', 'POST' => 'success']));
        $grid->column('path', '路径')->help('操作URL')->width(220)->sortable();
        $grid->column('ip', 'IP');
        $grid->column('input', '参数')->width(200);
        $grid->column('created_at', "操作时间")->customValue(function ($row, $value) {
            return date('Y-m-d H:i:s', strtotime($value));
        })->width(150)->sortable();

        $grid->actions(function (Grid\Actions $actions) {
            $actions->hideEditAction();
            $actions->hideViewAction();
            $actions->setDeleteAction(new Grid\Actions\DeleteDialogAction());
        })->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->hideCreateButton();
        });
        return $grid;
    }

    protected function form()
    {
        $form = new Form(new OperationLog());

        return $form;
    }
}
