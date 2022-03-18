<?php

namespace Andruby\DeepAdmin\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Andruby\DeepAdmin\Auth\Database\Menu;
use Andruby\DeepAdmin\Components\Attrs\SelectOption;
use Andruby\DeepAdmin\Components\Form\IconChoose;
use Andruby\DeepAdmin\Components\Form\InputNumber;
use Andruby\DeepAdmin\Components\Form\Select;
use Andruby\DeepAdmin\Components\Grid\Icon;
use Andruby\DeepAdmin\Components\Grid\Tag;
use Andruby\DeepAdmin\Form;
use Andruby\DeepAdmin\Grid;

class MenuController extends AdminController
{
    public function menuOrder(Request $request)
    {
        try {
            \Admin::validatorData($request->all(), [
                'self' => 'required',
                'target' => 'required',
                'type' => ['required', Rule::in(["before", "after", "inner"])],
            ]);

            $self_id = $request->input('self.id');
            $target_id = $request->input('target.id');
            $type = $request->input('type');
            $self_node = Menu::query()->findOrFail($self_id);
            $target_node = Menu::query()->findOrFail($target_id);

            switch ($type) {
                case "before":
                    Menu::query()->where('parent_id', $target_node->parent_id)
                        ->where('order', '>=', $target_node->order)
                        ->increment('order');
                    $self_node->parent_id = $target_node->parent_id;
                    $self_node->order = $target_node->order;
                    $self_node->save();
                    break;
                case "after":
                    Menu::query()->where('parent_id', $target_node->parent_id)
                        ->where('order', '>', $target_node->order)
                        ->increment('order');
                    $self_node->parent_id = $target_node->parent_id;
                    $self_node->order = $target_node->order + 1;
                    $self_node->save();
                    break;
                case "inner":
                    $self_node->parent_id = $target_node->id;
                    $self_node->order = 1;
                    $self_node->save();
                    break;
            }

        } catch (\Exception $exception) {
            return \Admin::responseError($exception->getMessage());
        }
    }

    protected function grid()
    {
        $userModel = config('deep_admin.database.menu_model');
        $grid = new Grid(new $userModel());

        $grid->addDialogForm($this->form()->isDialog()->className('p-15'));
        $grid->editDialogForm($this->form(true)->isDialog()->className('p-15'));

        $grid->model()->where('parent_id', 0);
        $grid->model()->with(['children', 'roles', 'children.roles']);

        $grid->defaultSort('order', 'asc')
            ->tree()
            ->quickSearch(["title"])
            ->quickSearchPlaceholder('名称')
            ->emptyText("暂无菜单");
        //->dialogForm($this->form()->isDialog()->labelPosition("top")->backButtonName("关闭"));

        $grid->column('id', "序号")->width(100)->sortable(true)->align('center');
        $grid->column('icon', "图标")->component(Icon::make())->width(80)->align('center');
        $grid->column('title', "名称");
        $grid->column('order', "排序")->width(80)->sortable(true);
        $grid->column('uri', "路径")->width(200);
        $grid->column('roles.name', "授权角色")->component(Tag::make());

        $grid->actions(function (Grid\Actions $actions) {
            $actions->setDeleteAction(new Grid\Actions\DeleteDialogAction());
        })->toolbars(function (Grid\Toolbars $toolbars) {

        });

        return $grid;
    }

    protected function form()
    {
        /**@var Model $model */
        $model = config('deep_admin.database.menu_model');
        $permissionModel = config('deep_admin.database.permissions_model');
        $roleModel = config('deep_admin.database.roles_model');

        $form = new Form(new $model());
        $form->getActions()->buttonCenter();
        $form->labelWidth('150px');

        $form->item('parent_id', '上级目录')->component(Select::make(0)->options(function () use ($model) {
            return $model::query()->where('parent_id', 0)->orderBy('order')->get()->map(function ($item) {
                return SelectOption::make($item->id, $item->title);
            })->prepend(SelectOption::make(0, '根目录'));
        }))->inputWidth(20);
        $form->item('title', '名称')->required()->inputWidth(15);
        $form->item('icon', trans('deep-admin::admin.icon'))->component(IconChoose::make())->inputWidth(15)->required();

        $form->item('uri', trans('deep-admin::admin.uri'))->required()->inputWidth(15);
        $form->item('order', trans('deep-admin::admin.order'))->component(InputNumber::make(1)->min(0));
        $form->item('roles', trans('deep-admin::admin.roles'))->component(Select::make()->block()->multiple()->options(function () use ($roleModel) {
            return $roleModel::all()->map(function ($role) {
                return SelectOption::make($role->id, $role->name);
            });
        }))->inputWidth(20);

        if ((new $model())->withPermission()) {
            $form->item('permission', trans('deep-admin::admin.permission'))->component(Select::make()->clearable()->block()->multiple()->options(function () use ($permissionModel) {
                return $permissionModel::all()->map(function ($role) {
                    return SelectOption::make($role->id, $role->name);
                });
            }))->inputWidth(20);
        };

        return $form;
    }
}
