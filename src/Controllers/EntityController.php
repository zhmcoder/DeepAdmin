<?php

namespace Andruby\DeepAdmin\Controllers;

use Andruby\DeepAdmin\Models\Entity;
use Andruby\DeepAdmin\TableHelpers;
use Andruby\DeepAdmin\Models\EntityField;
use Andruby\DeepAdmin\Components\Form\Checkbox;
use Andruby\DeepAdmin\Components\Form\CheckboxGroup;
use Andruby\DeepAdmin\Components\Form\CSwitch;
use Andruby\DeepAdmin\Components\Form\Input;
use Andruby\DeepAdmin\Components\Form\InputNumber;
use Andruby\DeepAdmin\Components\Form\Radio;
use Andruby\DeepAdmin\Form;
use Andruby\DeepAdmin\Grid;
use Andruby\DeepAdmin\Components\Form\RadioGroup;
use Andruby\DeepAdmin\Validates\EntityValidate;
use Illuminate\Support\Facades\DB;

class EntityController extends BaseController
{
    protected function grid()
    {
        $entitiesModel = config('deep_admin.database.entities_model');

        $grid = new Grid(new $entitiesModel());

        $grid->addDialogForm($this->form()->isDialog()->className('p-15'), '600px');
        $grid->editDialogForm($this->form(true)->isDialog()->className('p-15'), '600px');

        $grid->stripe()
            ->defaultSort('id', 'desc')
            ->perPage(env('PER_PAGE', 15))
            ->size(env('TABLE_SIZE', ''))
            ->border(env('TABLE_BORDER', false))
            ->emptyText("暂无模型");

        $grid->column('id', "序号")->width(120)->sortable()->align('center');

        $grid->column('name', "模型名称");
        $grid->column('table_name', '数据库表名');
        $grid->column('description', "描述");

        $grid->actions(function (Grid\Actions $actions) {
            $actions->add(Grid\Actions\ActionButton::make("数据")
                ->order(3)
                ->handler('route')
                ->uri('/entities/content?entity_id={id}')
            );
            $actions->add(Grid\Actions\ActionButton::make("字段管理")
                ->order(3)
                ->handler('route')
                ->uri('/entities/entity-field?entity_id={id}')
            );
            $actions->deleteAction()->popWidth();
        })->actionWidth(120);

        return $grid;
    }

    protected function do_form($is_edit = false)
    {
        $this->is_edit = $is_edit;

        $entitiesModel = config('deep_admin.database.entities_model');

        $form = new Form(new $entitiesModel());
        $form->getActions()->buttonCenter();

        $form->labelWidth('150px');

        $entityRequest = new EntityValidate();

        $form->item('name', '模型名称')
            ->inputWidth(20)
            ->required()
            ->serveRules($entityRequest->rules()['name'])
            ->serveRulesMessage($entityRequest->rules()['name']);

        $form->item('table_name', '数据库表名')
            ->component(Input::make()
                ->placeholder('模型对应的数据库表名称,保存后不能修改'))
            ->help('默认前缀' . config('deep_admin.database.prefix') . '，前缀如果需要修改，请修改配置文件')
            ->inputWidth(20)
            ->defaultValue(config('deep_admin.database.prefix'))
            ->required()
            ->serveRules($entityRequest->rules()['table_name'])
            ->serveRulesMessage($entityRequest->rules()['table_name']);

        if (!$this->is_edit) {
            $form->item('is_modify_db', '新建数据库表')
                ->component(CSwitch::make())
                ->help('某些情况下可能数据库表结构已经通过其它方式建好，此处无需操作数据库表，添加字段主要是方便利用框架提供的模型增删改查功能')
                ->inputWidth(20);
        }

        $form->item('description', '描述')
            ->component(Input::make())
            ->inputWidth(16);

        $form->item('default_sort', '默认排序字段')
            ->inputWidth(16)->defaultValue('id')
            ->help('排序的字段名称，支持单个字段排序');

        $form->item('sort_type', '排序类型')
            ->component(RadioGroup::make('desc', [Radio::make('desc', 'DESC'), Radio::make('asc', 'ASC')]))
            ->inputWidth(20)
            ->help('排序类型，支持 DESC、ASC');

        $form->item('actions', '支持操作')
            ->component(CheckboxGroup::make()->options(
                [
                    Checkbox::make('create', '添加')->checked(),
                    Checkbox::make('edit', '编辑')->checked(),
                    Checkbox::make('delete', '删除')->checked()
                ]
            ))->defaultValue(["create", "delete", "edit"])->inputWidth(24);

        $form->item('actions_width', '操作栏宽度')
            ->component(InputNumber::make()->min(100))->defaultValue(100);


        $form->item('actions_time_type', '操作时间格式')
            ->component(RadioGroup::make(1, [
                Radio::make(1, '日期时间'),
                Radio::make(2, '时间戳'),
            ]));

        $form->saving(function (Form $form) {
            if ($this->is_edit) {
                $id = $form->model()->getKey();
                $this->old_data = $form->model()->findOrFail($id);
            }
        });
        $form->saved(function (Form $form) {
            if (!$this->is_edit) {
                $this->saved_event($form);
            }
        });

        $form->deleting(function (Form $form, $id) {
            $this->deleting_event($id);
        });

        $form->DbTransaction(function (Form $form) {
            if ($this->is_edit) {
                $this->update_event($form, $this->old_data);
            }
        });

        return $form;
    }

    protected function deleting_event($id)
    {
        $table = Entity::query()->findOrFail($id);
        try {
            DB::beginTransaction();
            TableHelpers::drop_table($table->table_name);
            EntityField::query()->where('entity_id', $id)->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }

    protected function update_event(Form $form, $old_data)
    {
        $table_name = $form->input('table_name');
        $old_table_name = $old_data['table_name'];

        try {
            if ($table_name == $old_table_name) {
                return true; // 名字一样不更新
            }

            if (TableHelpers::check_exist_table($old_table_name)) {
                TableHelpers::rename_table($old_table_name, $table_name);
            } else {
                TableHelpers::create_table($table_name);
            }
        } catch (\Exception $e) {
            throw new \Exception('创建数据库表异常');
        }
    }

    protected function saved_event(Form $form)
    {
        $entity = $form->model();
        $createDB = $form->input('is_modify_db');
        $table_name = $form->input('table_name');
        try {
            if ($createDB) {
                TableHelpers::create_table($table_name);
            }
        } catch (\Exception $e) {
            $entity->delete();
            return \Admin::responseError("创建数据库表异常");
        }
    }

}
