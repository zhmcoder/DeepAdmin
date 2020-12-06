<?php

namespace Andruby\DeepAdmin\Controllers;

use Andruby\DeepAdmin\Models\Entity;
use Andruby\DeepAdmin\TableHelpers;
use Andruby\DeepAdmin\Models\EntityField;
use SmallRuralDog\Admin\Components\Form\Checkbox;
use SmallRuralDog\Admin\Components\Form\CheckboxGroup;
use SmallRuralDog\Admin\Components\Form\CSwitch;
use SmallRuralDog\Admin\Components\Form\Input;
use SmallRuralDog\Admin\Components\Form\InputNumber;
use SmallRuralDog\Admin\Components\Form\Radio;
use SmallRuralDog\Admin\Form;
use SmallRuralDog\Admin\Grid;
use SmallRuralDog\Admin\Components\Form\RadioGroup;
use Andruby\DeepAdmin\Validates\EntityValidate;
use Illuminate\Support\Facades\DB;

class EntityController extends BaseController
{

    protected function grid()
    {
        $entitiesModel = config('deep_admin.database.entities_model');

        $grid = new Grid(new $entitiesModel());

        $grid->defaultSort('id', 'desc');
        $grid->column('id', "ID")->width(80)->sortable();

        $grid->column('name', "模型名称");
        $grid->column('table_name', '数据库表名');
        $grid->column('description', "描述");
        // $grid->column('created_at', '添加时间');
        // $grid->column('updated_at', '更新时间');
        $grid->actions(function (Grid\Actions $actions) {
            $actions->add(Grid\Actions\ActionButton::make("数据")->order(3)
                ->handler('route')->uri('/entities/content?entity_id={id}'));
            $actions->add(Grid\Actions\ActionButton::make("字段管理")->order(3)
                ->handler('route')->uri('/entities/entity-field?entity_id={id}'));
        })->actionWidth(120);
        // $grid->column('is_show_content_manage', "状态")->width(100)->align("center")->component(Boole::make());

        return $grid;
    }

    protected function do_form($is_edit = false)
    {
        $this->is_edit = $is_edit;

        $entitiesModel = config('deep_admin.database.entities_model');

        $form = new Form(new $entitiesModel());

        $form->labelWidth('120px');

        $entityRequest = new EntityValidate();

        $form->item('name', '模型名称')
            ->inputWidth(8)
            ->required()
            ->serveRules($entityRequest->rules()['name'])
            ->serveRulesMessage($entityRequest->rules()['name']);

        $form->item('table_name', '数据库表名')
            ->component(Input::make()
                ->placeholder('模型对应的数据库表名称,保存后不能修改'))
            ->help('默认前缀' . config('deep_admin.database.prefix') . '，前缀如果需要修改，请修改配置文件')
            ->inputWidth(10)
            ->defaultValue(config('deep_admin.database.prefix'))
            ->required()
            ->serveRules($entityRequest->rules()['table_name'])
            ->serveRulesMessage($entityRequest->rules()['table_name']);

        if (!$this->is_edit) {
            $form->item('is_modify_db', '新建数据库表')
                ->component(CSwitch::make())
                ->help('某些情况下可能数据库表结构已经通过其它方式建好，此处无需操作数据库表，添加字段主要是方便利用框架提供的模型增删改查功能');
        }

        $form->item('description', '描述')
            ->component(Input::make())
            ->inputWidth(8);

        $form->item('default_sort', '默认排序字段')
            ->inputWidth(8)->defaultValue('id')
            ->help('排序的字段名称，支持单个字段排序');

        $form->item('sort_type', '排序类型')
            ->component(RadioGroup::make('desc', [Radio::make('desc', 'DESC'), Radio::make('asc', 'ASC')]))
            ->inputWidth(8)
            ->help('排序类型，支持 DESC、ASC');

        $form->item('actions', '支持操作')
            ->component(CheckboxGroup::make(["create", "edit", "delete"])->options(
                [
                    Checkbox::make('create', '添加')->checked(),
                    Checkbox::make('edit', '编辑')->checked(),
                    Checkbox::make('delete', '删除')->checked()
                ]
            ));

        $form->item('actions_width', '操作栏宽度')
            ->component(InputNumber::make()->min(100))->defaultValue(100);


        $form->item('actions_time_type', '操作时间格式')
            ->component(RadioGroup::make(1, [
                Radio::make(1, '日期时间'),
                Radio::make(2, '时间戳'),
            ]));

        /*
        $form->item('enable_comment', '评论')
            ->component(RadioGroup::make(1, [
                Radio::make(1, '是'),
                Radio::make(0, '否'),
            ]));
        */

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
        DB::beginTransaction();
        TableHelpers::drop_table($table->table_name);
        EntityField::query()->where('entity_id', $id)->delete();
        DB::commit();
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
