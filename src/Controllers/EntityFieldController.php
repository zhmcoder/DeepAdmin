<?php

namespace Andruby\DeepAdmin\Controllers;

use Andruby\DeepAdmin\Components\Grid\SortEdit;
use Andruby\DeepAdmin\Components\Grid\SortUpDown;
use Andruby\DeepAdmin\Components\Attrs\SelectOption;
use Andruby\DeepAdmin\Components\Form\CSwitch;
use Andruby\DeepAdmin\Components\Form\Input;
use Andruby\DeepAdmin\Components\Form\InputNumber;
use Andruby\DeepAdmin\Components\Form\Radio;
use Andruby\DeepAdmin\Components\Form\RadioGroup;
use Andruby\DeepAdmin\Components\Form\Select;
use Andruby\DeepAdmin\Components\Grid\Boole;
use Andruby\DeepAdmin\Components\Grid\Tag;
use Andruby\DeepAdmin\Controllers\AdminController;
use Andruby\DeepAdmin\Controllers\HasResourceActions;
use Andruby\DeepAdmin\Form;
use Andruby\DeepAdmin\Grid;
use Andruby\DeepAdmin\TableHelpers;
use Andruby\DeepAdmin\Models\EntityField;
use Andruby\DeepAdmin\Models\Entity;
use Andruby\DeepAdmin\Validates\EntityFieldValidate;

class EntityFieldController extends AdminController
{
    use HasResourceActions;

    protected function grid()
    {
        $entityFieldModel = config('deep_admin.database.entity_fields_model');
        $isFormShow = config('deep_admin.is_form_show');

        $data = request()->all();
        $entity_id = $data['entity_id'];

        $grid = new Grid(new $entityFieldModel());

        $grid->addDialogForm($this->form()->isDialog()->className('p-15'), '800px');
        $grid->editDialogForm($this->form(true)->isDialog()->className('p-15'), '800px');

        $grid->model()->where('entity_id', $entity_id);

        $grid->quickSearch(['name'])
            ->quickSearchPlaceholder("字段名称")
            ->pageBackground()
            ->fit(true)
            ->stripe()
            ->defaultSort('list_order', 'asc')
            ->perPage(env('PER_PAGE', 15))
            ->size(env('TABLE_SIZE', ''))
            ->border(env('TABLE_BORDER', false))
            ->emptyText("暂无模型字段");

        $grid->toolbars(function (Grid\Toolbars $toolbars) use ($entity_id) {
            /*
            $toolbars->hideCreateButton();
            $toolbars->addRight(Grid\Tools\ToolButton::make("添加")
                ->icon("el-icon-plus")
                ->handler(Grid\Tools\ToolButton::HANDLER_ROUTE)
                ->uri("/entities/entity-field/create?entity_id=" . $entity_id));
            */
        })->actions(function (Grid\Actions $actions) {
            $actions->setDeleteAction(new Grid\Actions\DeleteDialogAction());
        })->actionWidth(120)->actionFixed('right');

        $grid->column('id', "序号")->width(120)->sortable()->align('center');
        $grid->column('entity.name', "模型")->width(150);
        $grid->column('name', "字段名称")->width(150);
        $grid->column('type', '字段类型')->width(120);
        $grid->column('form_name', "表单名称")->width(150);
        $grid->column('form_type', '表单类型')->sortable()->width(150);
        //$grid->column('is_show_inline', "行内展示")->align("center")->component(Boole::make())->sortable();
        $grid->column('is_show', "表单显示")
            ->align("center")
            ->customValue(function ($row, $value) use ($isFormShow) {
                return $isFormShow[$value];
            })->component(function () {
                return Tag::make()->type('info');
            })->sortable()->width(120);
        $grid->column('order', '表单排序')->component(
            SortEdit::make()->action(config('deep_admin.route.api_prefix') . '/entities/content/grid_sort_change?column=order&entity_id=2')
        )->align("center")->sortable()->width(120);
        $grid->column('list_order', '列表排序')->component(
            SortEdit::make()->action(config('deep_admin.route.api_prefix') . '/entities/content/grid_sort_change?column=list_order&entity_id=2')
        )->align("center")->sortable()->width(120);
        $grid->column('list_width', '列表宽度')->component(
            SortEdit::make()->action(config('deep_admin.route.api_prefix') . '/entities/content/grid_sort_change?column=list_width&entity_id=2')
        )->align("center")->sortable()->width(120);
        $grid->column('is_list_show', '列表显示')->align("center")->component(Boole::make())->sortable()->width(120);;
        //$grid->column('is_show', '显示')->component(CSwitch::make())->sortable();

        return $grid;
    }

    protected function form($isEdit = 0): Form
    {
        $entityFieldModel = config('deep_admin.database.entity_fields_model');
        $isFormShow = config('deep_admin.is_form_show');
        $isSearch = config('deep_admin.is_search');

        $fieldType = config('deep_admin.db_table_field_type');
        $formType = config('deep_admin.form_type');

        $entity_id = request('entity_id');

        $entityFieldRequest = new EntityFieldValidate();
        $form = new Form(new $entityFieldModel());
        $form->getActions()->buttonCenter();

        $form->hideTab(false);

        $form->labelWidth('150px');
        $form->labelPosition('right')->statusIcon(true);

//        $form->item('entity_id', '模型')
//            ->component(
//                Select::make()
//                    ->disabled($isEdit)
//                    ->options(function () use ($entitiesModel) {
//                        return $entitiesModel::all()->map(function ($entities) {
//                            return SelectOption::make($entities->id, $entities->name);
//                        });
//                    })
//            )->serveRules($entityFieldRequest->rules()['entity_id'])
//            ->serveRulesMessage($entityFieldRequest->messages()['entity_id'])
//            ->required();

        $form->item('entity_id', '模型')
            ->component(Input::make()->disabled())
            ->defaultValue($entity_id)
            ->inputWidth(12)
            ->serveRules($entityFieldRequest->rules()['entity_id'])
            ->serveRulesMessage($entityFieldRequest->messages()['entity_id']);

        $form->item('name', '字段名称')
            ->component(Input::make()->placeholder('只能包含英文字母和数字，长度不超过64'))
            ->inputWidth(12)
            ->serveRules($entityFieldRequest->rules()['name'])
            ->serveRulesMessage($entityFieldRequest->messages()['name'])
            ->required();

        $form->item('type', '字段类型')
            ->help("以MySQL数据库为例：string类型对应VARCHAR；char类型对应CHAR")
            ->component(
                Select::make()
                    ->filterable()
                    //->disabled($isEdit)
                    ->options(function () use ($fieldType) {
                        $return = [];
                        foreach ($fieldType as $key => $val) {
                            $return[] = SelectOption::make($key, $val);
                        }
                        return $return;
                    })
            )->required()->inputWidth(20);

        $form->item('min', '最小值')
            ->component(InputNumber::make(256)->min(0))
            ->defaultValue(0)
            ->vif('type', 'integer')
            ->help('对于整数类型的字段，最小值，默认0');

        $form->item('max', '最大值')
            ->component(InputNumber::make(256)->min(0))
            ->defaultValue(0)
            ->vif('type', 'integer')
            ->help('对于整数类型的字段，最大值，默认0');

        $form->item('field_length', '字段长度')
            ->component(InputNumber::make(256)->min(1))
            ->defaultValue(100)
            ->vif('type', 'string')
            ->help('对于char、string类型的字段，请在此输入字段长度');

        $form->item('field_length', '字段长度')
            ->component(InputNumber::make(10)->min(1)->max(256))
            ->defaultValue(10)
            ->vif('type', 'char')
            ->help('对于char、string类型的字段，请在此输入字段长度');

        $form->item('field_total', '整数长度')
            ->component(InputNumber::make(9)->min(0))
            ->defaultValue(4)
            ->vif('type', 'decimal')
            ->inputWidth(6)
            ->help('对于浮点数类型的字段，请在此输入总位数');
        $form->item('field_scale', '小数长度')
            ->component(InputNumber::make(4)->min(0))
            ->defaultValue(0)
            ->vif('type', 'decimal')
            ->inputWidth(6)
            ->help('对于浮点数类型的字段，请在此输入小数位数');

        $form->item('default_value', '字段默认值')
            ->component(Input::make()->placeholder('仅对字符串、数值类型的字段类型有效'))
            ->inputWidth(12);
        $form->item('comment', '字段注释')
            ->inputWidth(12)
            ->required();

        $form->item('is_modify_db', '变更表结构')
            ->component(CSwitch::make())
            ->defaultValue(!$isEdit)
            ->help('某些情况下可能数据库表结构已经通过其它方式建好，此处无需操作数据库表，添加字段主要是方便利用框架提供的模型增删改查功能')
            ->inputWidth(20);

        $form->item('form_name', '表单名称')
            ->inputWidth(12)
            ->required();
        $form->item('form_comment', '表单备注')->inputWidth(12);
        $form->item('form_type', '表单类型')
            ->help('下拉选择（远程搜索）、下拉选择（多选，远程搜索）只支持行内展示')
            ->component(
                Select::make()
                    ->filterable()
                    ->options(function () use ($formType) {
                        $return = [];
                        foreach ($formType as $key => $val) {
                            $return[] = SelectOption::make($key, $val);
                        }
                        return $return;
                    })
            )->required()->inputWidth(20);

        $form->item('children_id', '级联子节点')
            ->component(InputNumber::make())
            ->vif('form_type', 'cascade')
            ->help('级联子节点');
        $form->item('children_id', '级联子节点')
            ->component(InputNumber::make())
            ->vif('form_type', 'cascadeMulti')
            ->help('级联子节点');

        $form->item('form_params', '表单参数')
            ->component(Input::make()->textarea(5)->placeholder('对于表单类型为单选框、多选框、下拉选择的，需在此配置对应参数。参数格式为：key=value，多个以换行分隔。也可以填写自定义的函数名称，函数名称需以getFormItemsFrom开头，返回值需与前述数据格式一致。对于下拉选择远程搜索表单类型、短文本（input，自动完成）表单类型，需在此填写后端接口URL地址，接口返回数据格式可参考文档说明。'))
            ->inputWidth(20);

        $form->item('table_where', '连表查询条件')
            ->component(Input::make()->textarea(4)->placeholder('连表查询条件，参数格式为：字段名,条件,值'))
            ->inputWidth(20);

        $form->item('prop', '列表显示表字段')->inputWidth(12)->tab('高级配置');

        $form->item('order', '表单排序')->help('值越小排序越靠前')->component(InputNumber::make(100)->min(0))->tab('高级配置');

        $form->item('list_order', '列表排序')->help('值越小排序越靠前')->component(InputNumber::make(100)->min(0))->tab('高级配置');

        $radio = [];
        foreach ($isFormShow as $key => $val) {
            $radio[] = Radio::make($key, $val);
        }
        $form->item('is_show', '表单显示')->component(RadioGroup::make(4, $radio))->inputWidth(20)->tab('高级配置');

        $form->item('show_where', '表单显示条件')
            ->component(Input::make()->textarea(2)->placeholder('表单显示条件，参数格式为：字段名,条件,值'))
            ->inputWidth(15)->tab('高级配置');

        $radio = [];
        foreach ($isSearch as $key => $val) {
            $radio[] = Radio::make($key, $val);
        }
        $form->item('is_search', '支持查询方式')->component(RadioGroup::make(1, $radio))->inputWidth(20)->tab('高级配置');

        $form->item('is_list_show', '列表显示')->component(CSwitch::make())->tab('高级配置');

        $form->item('is_show_inline', '行内展示')->component(CSwitch::make())->tab('高级配置');

        $form->item('is_order', '列表是否支持排序')->component(CSwitch::make())->tab('高级配置');

        $form->item('is_required', '是否必填')->component(CSwitch::make())->defaultValue(0)->tab('高级配置');

        $form->item('is_unique', '是否唯一')->component(CSwitch::make())->defaultValue(0)->tab('高级配置');

        $form->item('input_width', '表单宽度')->help('表彰宽度默认220px，单位px')
            ->component(InputNumber::make(220)->max(500)->min(0))->tab('高级配置');

        $form->item('list_width', '列表宽度')->help('列表宽度设置为0则自适应')->component(InputNumber::make(0)->max(500)->min(0))->tab('高级配置');

        $form->item('vif_name', '关联字段名称')->inputWidth(12)->tab('高级配置');
        $form->item('vif_value', '关联字段值')->inputWidth(12)->tab('高级配置');

        $form->item('desc', '下拉副标题')->inputWidth(12)->tab('定制配置');
        $form->item('desc_prefix', '下拉副标题前缀')->inputWidth(12)->tab('定制配置');
        $form->item('desc_suffix', '下拉副标题后缀')->inputWidth(12)->tab('定制配置');

        $form->saved(function (Form $form) use ($isEdit) {
            if (!$isEdit) {
                $this->saved_event($form);
            }
            if ($isEdit) {
                $this->update_event($form, $this->old_data);
            }
        });

        $form->saving(function (Form $form) use ($isEdit) {
            if ($isEdit) {
                $id = $form->model()->getKey();
                $this->old_data = $form->model()->findOrFail($id);
            }

        });

        $form->deleting(function (Form $form, $id) {
            $this->deleting_event($id);
        });

        return $form;
    }

    protected function deleting_event($id)
    {
        $entityField = EntityField::find($id);
        $entities = Entity::find($entityField->entity_id);

        TableHelpers::drop_field($entities->table_name, $entityField->name);
    }

    protected function saved_event(Form $form)
    {
        $entityField = $form->model();
        $isModifyDB = request()->post('is_modify_db');

        $fieldInfo = request()->all();

        $entityInfo = Entity::find($fieldInfo['entity_id']);
        try {
            if ($isModifyDB) {
                TableHelpers::create_field($entityInfo->table_name, $fieldInfo);
            }
        } catch (\Exception $e) {
            $entityField->delete();
            return \Admin::responseError("创建表字段异常");
        }
    }

    protected function update_event(Form $form, $oldData)
    {
        $isModifyDB = request()->post('is_modify_db');
        $fieldInfo = request()->all();

        $entityInfo = Entity::find($fieldInfo['entity_id']);

        if ($isModifyDB) {
            // 旧字段是否存在
            if (TableHelpers::check_exist_table_field($entityInfo->table_name, $oldData->name)) {
                if ($oldData->name != $fieldInfo['name']) {
                    TableHelpers::rename_field($entityInfo->table_name, $oldData->name, $fieldInfo['name']);
                }
                TableHelpers::update_field($entityInfo->table_name, $fieldInfo);

            } else {
                TableHelpers::create_field($entityInfo->table_name, $fieldInfo);
            }
        }
    }

}
