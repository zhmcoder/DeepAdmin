<?php

namespace Andruby\DeepAdmin\Controllers;

use Andruby\DeepAdmin\Components\Grid\DeepLink;
use Andruby\DeepAdmin\Components\Grid\SortEdit;
use Andruby\DeepAdmin\Components\Grid\SortUpDown;
use Andruby\DeepAdmin\Models\ContentTimeStamp;
use Andruby\DeepAdmin\Services\GridCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use SmallRuralDog\Admin\Components\Attrs\SelectOption;
use SmallRuralDog\Admin\Components\Form\Cascader;
use SmallRuralDog\Admin\Components\Form\Checkbox;
use SmallRuralDog\Admin\Components\Form\CheckboxGroup;
use SmallRuralDog\Admin\Components\Form\ColorPicker;
use SmallRuralDog\Admin\Components\Form\CSwitch;
use SmallRuralDog\Admin\Components\Form\DatePicker;
use SmallRuralDog\Admin\Components\Form\DateTimePicker;
use SmallRuralDog\Admin\Components\Form\Input;
use SmallRuralDog\Admin\Components\Form\InputNumber;
use SmallRuralDog\Admin\Components\Form\Radio;
use SmallRuralDog\Admin\Components\Form\RadioGroup;
use SmallRuralDog\Admin\Components\Form\Select;
use SmallRuralDog\Admin\Components\Form\TimePicker;
use SmallRuralDog\Admin\Components\Form\Upload;
use SmallRuralDog\Admin\Components\Form\WangEditor;
use SmallRuralDog\Admin\Components\Grid\Avatar;
use SmallRuralDog\Admin\Components\Grid\Boole;
use SmallRuralDog\Admin\Components\Grid\Image;
use SmallRuralDog\Admin\Components\Grid\Tag;
use SmallRuralDog\Admin\Controllers\AdminController;
use SmallRuralDog\Admin\Controllers\HasResourceActions;
use SmallRuralDog\Admin\Facades\Admin;
use SmallRuralDog\Admin\Form;
use SmallRuralDog\Admin\Grid;
use Andruby\DeepAdmin\Models\Content;
use Andruby\DeepAdmin\Models\Entity;
use Andruby\DeepAdmin\Models\EntityField;

class ContentController extends AdminController
{
    use HasResourceActions;

    protected $entityId = null; //
    protected $entity = null; // 模型
    protected $entityField = null; // 模型字段
    protected $remoteUrl = '';
    protected $uploadImages = '';
    protected $createUrl = "/entities/content/create?";
    protected $dataUrl = "";

    public function __construct()
    {
        $this->entityId = request('entity_id');
        if ($this->entityId) {
            $this->entity = Entity::find($this->entityId);
//            $this->entityField = EntityField::query()->where('entity_id', $this->entityId)->get()->toArray();
        } else {
            if ($this->getTableName()) {
                $this->entity = Entity::where('table_name', $this->getTableName())->firstOr();
                $this->entityId = $this->entity->id;
            }
        }

        $this->remoteUrl = config('admin.route.api_prefix') . '/remote/search'; // 下拉远程搜索
        $this->uploadImages = config('admin.route.api_prefix') . '/upload/images'; // 编辑框上传图片
    }

    protected function getTableName()
    {
        return null;
    }

    protected function grid()
    {
        $grid = new Grid(new Content($this->entity->table_name));

        $defaultSort = !empty($this->entity->default_sort) ? $this->entity->default_sort : 'id';
        $sortType = !empty($this->entity->sort_type) ? $this->entity->sort_type : 'desc';
        $actionsInfo = !empty($this->entity->actions) ? $this->entity->actions : [];
        $actionsWidth = !empty($this->entity->actions_width) ? $this->entity->actions_width : 100;

        $grid->pageBackground()
            ->defaultSort($defaultSort, $sortType, $defaultSort)
            ->stripe(true)
            ->fit(true)
            ->defaultSort('id', 'desc')
            ->perPage(env('PER_PAGE', 15))
            ->size(env('TABLE_SIZE', ''))
            ->border(env('TABLE_BORDER', false))
            ->emptyText("暂无数据");

        $time = time();

        $entityId = $this->entityId;
        $grid->toolbars(function (Grid\Toolbars $toolbars) use ($entityId, $actionsInfo, $time) {
            if (!empty($actionsInfo) && in_array('create', $actionsInfo)) {
                $uri = explode('?', request()->getRequestUri());
                $params = isset($uri[1]) ? $uri[1] : '';
                $toolbars->createButton()->params($params)->content('添加');
            }

            $this->grid_toolbars($toolbars);
        });

//        $grid->quickFilter()->filterKey('status')
//            ->quickOptions([
//                Radio::make('1', '正常'),
//                Radio::make('2', '过期'),
//                Radio::make('3', '审核')]);

        $entityField = EntityField::query()->where('entity_id', $this->entityId)
            ->where('is_search', '>', 1)->orderBy('list_order', 'asc')->orderBy('id', 'asc')->get()->toArray();
        $grid->filter(function ($filter) use ($entityField) {
            foreach ($entityField as $key => $val) {
                if (!$this->isShow($val)) {
                    continue;
                }

                $val['name'] = !empty($val['prop']) ? $val['prop'] : $val['name'];

                $defaultValue = request($val['name']); // todo 关联查询
                $defaultValue = isset($defaultValue) ? $defaultValue : null;
                $defaultValue = ($val['type'] == 'integer') ? (int)$defaultValue : (string)$defaultValue;

                $width = !empty($val['list_width']) ? $val['list_width'] : 120;
                $disabled = ($val['is_search'] == 5) ? true : false;

                if ($val['is_search'] == 3) {
                    $filter->like($val['name'], $val['form_name'])->component(
                        Input::make($defaultValue)->clearable()->style('width:' . $width . 'px;margin-left:5px;')
                    );
                }

                if ($val['is_search'] == 4 || $val['is_search'] == 5) {
                    if (in_array($val['form_type'], ['hidden', 'option', 'selectMulti', 'select', 'checkbox'])) {
                        $component = Select::make($defaultValue)->filterable()->options($this->_selectOption($val))->clearable()->style('width:' . $width . 'px;margin-left:5px;')->disabled($disabled);

                        if (in_array($val['form_type'], ['selectMulti', 'checkbox'])) { // 多选、复选
                            $filter->like($val['name'], $val['form_name'])->component($component);
                        } else {
                            $filter->equal($val['name'], $val['form_name'])->component($component);
                        }
                    }
                    if (in_array($val['form_type'], ['selectMultiTable', 'selectTable', 'checkboxTable'])) {
                        $component = Select::make($defaultValue)->filterable()->options($this->_selectOptionTable($val))->clearable()->style('width:' . $width . 'px;margin-left:5px;')->disabled($disabled);

                        if (in_array($val['form_type'], ['selectMultiTable', 'checkboxTable'])) {
                            $filter->like($val['name'], $val['form_name'])->component($component);
                        } else {
                            $filter->equal($val['name'], $val['form_name'])->component($component);
                        }
                    }
                    if (in_array($val['form_type'], ['selectRemote'])) {
                        $filter->equal($val['name'], $val['form_name'])->component(
                            Select::make($defaultValue)->filterable()->placeholder('请输入查询')
                                ->remote($this->remoteUrl)->extUrlParams(['val' => $val])->paginate(10)
                                ->clearable()->style('width:' . $width . 'px;margin-left:5px;')->disabled($disabled)
                        );
                    }
                }
            }
        });

        // 编辑参数
        $except = ['get_data', 'page', 'per_page', 'sort_prop', 'sort_order', 'sort_field'];
        $uri = request()->except($except);
        $editParams = '';
        if (!empty($uri)) {
            foreach ($uri as $key => $val) {

                // 默认查询条件
                if (strpos($key, 'default_') !== false) {
                    if (Schema::hasColumn($this->entity->table_name, str_replace('default_', '', $key))) {
                        $grid->model()->where(str_replace('default_', '', $key), $val);
                    }
                }

                if (is_array($val)) {
                    $key .= '[]';
                    foreach ($val as $v) {
                        $editParams .= $key . '=' . $v . '&';
                    }
                } else {
                    $editParams .= $key . '=' . $val . '&';
                }
            }
        }

        $grid->actions(function (Grid\Actions $actions) use ($actionsInfo, $editParams, $time) {
            // 隐藏编辑操作
            if (!empty($actionsInfo) && in_array('edit', $actionsInfo)) {
                $actions->editAction()->params($editParams . 'version=' . $time);
            } else {
                $actions->hideEditAction();
            }

            // 隐藏删除操作
            if (!empty($actionsInfo) && in_array('delete', $actionsInfo)) {
                $actions->deleteAction()->params('entity_id=' . $this->entityId . '&version=' . $time);
            } else {
                $actions->hideDeleteAction();
            }
            $actions->setDeleteAction(new Grid\Actions\DeleteDialogAction());

            $this->grid_action($actions);
        })->actionWidth($actionsWidth)->actionFixed('right');

        // 隐藏操作栏
        if (empty($actionsInfo) || (!in_array('edit', $actionsInfo) && !in_array('delete', $actionsInfo))) {
            $grid->hideActions();
        }

        // 是否显示ID字段
        if (Schema::hasColumn($this->entity->table_name, 'id')) {
            $grid->column('id', '序号')->sortable()->width(120)->align('center');
        }

        $entityField = EntityField::query()->where('entity_id', $this->entityId)
            ->where('is_list_show', 1)
            ->orderBy('list_order', 'asc')
            ->orderBy('id', 'asc')
            ->get()->toArray();

        foreach ($entityField as $key => $val) {
            if (!$this->isShow($val)) {
                continue;
            }

            $obj = $grid->column($val['name'], $val['form_name'], !empty($val['prop']) ? $val['prop'] : null);

            $listWidth = isset($val['list_width']) ? $val['list_width'] : 0;

            ($val['is_order']) ? $obj->sortable() : ''; // 支持排序
            ($listWidth == 0) ? $obj->minWidth("120") : $obj->width($listWidth); // 小最宽度

            switch ($val['form_type']) {
                case 'input' :
                    if ($listWidth >= 200) {
                        $obj->component(function () {
                            return Tag::make()->type('info');
                        });
                    }
                    break;

                case 'inputNumber' :
                case 'inputDecimal' :
                    $obj->help($val['form_comment'])->align('center');
                    if (!empty($val['form_params'])) {
                        $obj->component(function () use ($val) {
                            return Tag::make()->type($val['form_params']);
                        });
                    }
                    break;

                case 'switch' :
                    $obj->align("center")->component(Boole::make());
                    break;

                case 'option' :
                case 'select' :
                    $formParams = explode("\n", $val['form_params']);
                    $newParams = [];
                    $type = [];
                    foreach ($formParams as $k => &$v) {
                        $v = explode('=', $v);
                        $newParams[$v[0]] = $v[1];
                        $type[$v[1]] = isset($v[2]) ? $v[2] : 'info';
                    }
                    $obj->customValue(function ($row, $value) use ($newParams) {
                        return isset($newParams[$value]) ? $newParams[$value] : '';
                    })->component(function () use ($type) {
                        return Tag::make()->type($type);
                    });
                    break;

                case 'selectTable' :
                case 'selectRemote' :
                    debug_log_info($val['form_params']);
                    $formParams = explode("\n", $val['form_params']);

                    $obj->customValue(function ($row, $value) use ($formParams, $val) {
                        $value = (!empty($val['prop'])) ? $row[$val['prop']] : $value; // 使用字段

                        $return = [];
                        if (!empty($formParams[0]) && !empty($formParams[1]) && !empty($formParams[2])) {
                            $model = new Content($formParams[0]);
                            if (!empty($value)) {
                                $return[] = GridCacheService::instance()
                                    ->get_cache_value($model, $formParams[0] . '_' . $formParams[1] . '_' . $value,
                                        $value, $formParams[1], $formParams[2]);;
                            }
                        }
                        return $return;
                    })->component(function () {
                        return Tag::make()->type('success');
                    });
                    break;

                case 'selectMulti' :
                case 'checkbox' :
                case 'checkboxTable' :
                    $formParams = explode("\n", $val['form_params']);
                    $newParams = [];
                    foreach ($formParams as $k => &$v) {
                        $v = explode('=', $v);
                        $newParams[$v[0]] = $v[1];
                    }
                    $obj->customValue(function ($row, $value) use ($newParams) {
                        $return = [];
                        if (!empty($value)) {
                            foreach ($value as $v) {
                                if (isset($newParams[$v])) {
                                    $return[] = $newParams[$v];
                                }
                            }
                        }
                        return $return;
                    })->component(function () {
                        return Tag::make()->type('success');
                    });
                    break;

                case 'upload' :
                    $obj->align('center')->component(Image::make()->preview()->style('height:40px;width:40px;'));
                    break;

                case 'avatar' :
                    $obj->align('center')->component(Avatar::make()->size('number'));
                    break;

                case 'uploadFile' :
                    $obj->customValue(function ($row, $value) {
                        return !empty($value) ? 'storage/' . $value : '';
                    })->align('center')
                        ->component(DeepLink::make()->underline()->type('primary')->target('_blank'));
                    break;

                case 'dateTime' :
                case 'date' :
                    break;

                case 'selectMultiTable' :
                    $formParams = explode("\n", $val['form_params']);
                    $model = new Content($formParams[0]);
                    $list = $model->get()->toArray();
                    $listArr = [];
                    foreach ($list as $k => $v) {
                        $listArr[$v[$formParams[1]]] = $v[$formParams[2]];
                    }
                    $obj->customValue(function ($row, $value) use ($listArr) {
                        $return = [];
                        if (!empty($value)) {
                            foreach ($value as $v) {
                                if (isset($listArr[$v])) {
                                    $return[] = $listArr[$v];
                                }
                            }
                        }
                        return $return;
                    })->component(function () {
                        return Tag::make()->type('success');
                    });
                    break;

                case 'textArea' :
                    $obj->component(function () {
                        return Tag::make()->type('info');
                    });
                    break;

                case 'countTable' : // 统计数量
                    $formParams = explode("\n", $val['form_params']);
                    $model = new Content($formParams[0]);
                    $obj->customValue(function ($row) use ($model, $formParams) {
                        if (!empty($formParams[1])) { // 关联字段
                            $model = $model->where($formParams[1], $row['id']);
                        }
                        if (!empty($formParams[2]) && !empty($formParams[3])) { // join on
                            $model = $model->join($formParams[2], function ($join) use ($formParams) {
                                $joinOn = explode(",", $formParams[3]);
                                $join->on($joinOn[0], '=', $joinOn[1]);
                                if (!empty($formParams[4])) {
                                    $join->where(json_decode($formParams[4], true)); // where
                                }
                            });
                        }
                        $count = $model->count();
                        return $count;
                    })->component(function () {
                        return Tag::make()->type('success');
                    })->help($val['form_comment'])->align('center');
                    break;

                case 'timestamp' : // 时间戳
                    $obj->customValue(function ($row, $value) {
                        return date('Y-m-d H:i:s', $value);
                    });
                    break;

            }

            if ($val['name'] == 'sort') {
                $obj->component(
                // SortUpDown::make(110)->setSortAction(config('admin.route.api_prefix') . '/entities/content/sort_up_down?entity_id=' . $entityId)
                    SortEdit::make()->action(config('admin.route.api_prefix') . '/entities/content/grid_sort_change?entity_id=' . $entityId)
                );
            }
        }

        //$grid->actions(function (Grid\Actions $actions) {})->actionWidth(50);
        if (!empty($this->dataUrl)) {
            $grid->dataUrl($this->dataUrl);
        }

        $grid = $this->grid_list($grid);

        return $grid;
    }

    public function grid_sort_change()
    {
        $id = request('id');

        $column = request('column', 'sort');
        $sort_value = request('sort_value');

        if (empty($id) || empty($sort_value)) {
            return \Admin::responseError("参数错误");
        } else {
            $form = new Form(new Content($this->entity->table_name));
            $form->model()->where('id', $id)->update(array($column => $sort_value));
            return \Admin::responseMessage("保存成功");
        }
    }

    public function sort_up_down(Request $request)
    {
        $current_id = request('current_id');
        $sort_type = request('sort_type');//值up,down

        if ($request->has('change_id')) {
            $change_id = $sort_type = request('change_id');
        } else {
            $change_id = null;
        }

        if (empty($current_id) || empty($sort_type)) {
            return \Admin::responseError("参数错误");
        } else {
            $form = new Form(new Content($this->entity->table_name));
            if (empty($change_id)) {
                $current_sort = $form->model()->where('id', $current_id)->value('sort');
                if ($sort_type == 'up') {
                    $info = $form->model()->where('id', '<', $current_sort)
                        ->first(['sort', 'id']);
                    if ($info) {
                        $change_sort = $info['sort'];
                        $change_id = $info['id'];
                    }
                } else {
                    $info = $form->model()->where('id', '>', $current_sort)
                        ->first(['sort', 'id']);
                    if ($info) {
                        $change_sort = $info['sort'];
                        $change_id = $info['id'];
                    }
                }
                if ($info) {
                    $form->model()->where('id', $current_id)->update(['sort' => $change_sort]);
                    $form->model()->where('id', $change_id)->update(['sort' => $current_sort]);
                }

            } else {
                $current_sort = $form->model()->where('id', $current_id)->value('sort');
                $change_sort = $form->model()->where('id', $change_id)->value('sort');
                $form->model()->where('id', $current_id)->update(['sort' => $change_sort]);
                $form->model()->where('id', $change_id)->update(['sort' => $current_sort]);
            }

            return \Admin::responseMessage("保存成功");
        }
    }

    protected function form($isEdit = 0)
    {
        if (isset($this->entity->actions_time_type) && $this->entity->actions_time_type == 2) {
            // 创建时间、更新时间 字段重新定义
            $form = new Form(new ContentTimeStamp($this->entity->table_name));
        } else {
            $form = new Form(new Content($this->entity->table_name));
        }
        $form->getActions()->buttonCenter();

        $form->labelPosition('right')->statusIcon(true)->labelWidth('150px');

        $form->actionParams(['entity_id' => $this->entityId]);
//        $form->action($form->getAction() . "?entity_id=" . $this->entityId);

        $entityField = EntityField::query()->where('entity_id', $this->entityId)
            ->whereIn('is_show', $isEdit ? [2, 4] : [3, 4])
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get()->toArray();

        $cascadeFields = [];

        foreach ($entityField as $key => $val) {

            // 是否显示表单
            if (!$this->isShow($val)) {
                continue;
            }

            if (!empty($val['prop'])) {
                $obj = $form->item($val['prop'], $val['form_name'])->help($val['form_comment']);
            } else {
                $obj = $form->item($val['name'], $val['form_name'])->help($val['form_comment']);
            }

            // 关联显示字段
            if (!empty($val['vif_name']) && isset($val['vif_value'])) {
                $obj = $obj->vif($val['vif_name'], $val['vif_value']);
            }

            $isUnique = isset($val['is_unique']) ? $val['is_unique'] : false;
            $disabled = ($val['is_search'] == 5) ? true : false;

            if (!empty(request($val['name']))) {
                $defaultValue = request($val['name']);
            } else if (!empty(request($val['name']))) {
                $defaultValue = request($val['name']);
            } else {
                $defaultValue = $val['default_value'];
            }

            if ($val['type'] == 'integer') {
                $defaultValue = (int)$defaultValue;
            } else if (!is_array($defaultValue)) {
                $defaultValue = (string)$defaultValue;
            }

            switch ($val['form_type']) {
                case 'input' :
                    $obj->component(Input::make()->placeholder($val['form_comment'])->maxlength($val['field_length'])->showWordLimit())
                        ->inputWidth(100)
                        ->help('')
                        ->required($val['is_required'], 'string')
                        ->unique($isUnique, $this->entity->table_name, $val['name'], $val['comment'])
                        ->max($val['field_length'])->defaultValue($defaultValue);
                    break;

                case 'inputNumber' : // 整数
                    $min = isset($val['min']) ? $val['min'] : 0;
                    $max = isset($val['max']) ? $val['max'] : 99999999999;
                    $obj->component(InputNumber::make()->min($min)->max($max)->controlsPosition(''))->help($val['form_comment'])->defaultValue($defaultValue)->required($val['is_required'], 'number');
                    break;

                case 'inputDecimal' : // 小数
                    $obj->component(InputNumber::make()->precision($val['field_scale'])->min(0)->controlsPosition(''))->help($val['form_comment'])->required($val['is_required'], 'float');
                    break;

                case 'option' : // 单选
                    $obj->component(
                        RadioGroup::make($defaultValue, $this->_radio($val))->disabled($disabled)
                    )->required($val['is_required'], 'number');
                    break;

                case 'select' : // 下拉单选
                    $obj->component(
                        Select::make($defaultValue)->filterable()->options($this->_selectOption($val))->clearable()->disabled($disabled)
                    )->inputWidth(20)->required($val['is_required'], 'number');
                    break;

                case 'selectTable' : // 下拉单选 连表查询
                    $obj->component(
                        Select::make()->filterable()->options($this->_selectOptionTable($val))->clearable()
                    )->inputWidth(10)->required($val['is_required'], 'number')
                        ->unique($isUnique, $this->entity->table_name, $val['name'], $val['comment']);
                    break;

                case 'selectMulti' : // 下拉多选
                    $obj->component(
                        Select::make()->filterable()->multiple(true)->options($this->_selectOption($val))
                    )->inputWidth(10)->required($val['is_required'], 'array');
                    break;

                case 'selectMultiTable' : // 下拉多选 连表查询
                    $obj->component(
                        Select::make()->filterable()->multiple(true)->options($this->_selectOptionTable($val))
                    )->inputWidth(10)->required($val['is_required'], 'array');
                    break;

                case 'checkbox' : // 复选框
                    $formParams = explode("\n", $val['form_params']);
                    $checkbox = [];
                    foreach ($formParams as $k => &$v) {
                        $v = explode('=', $v);
                        $checkbox[] = Checkbox::make($v[0], $v[1]);
                    }
                    $obj->component(
                        CheckboxGroup::make()->options($checkbox)->min(0)->max(count($checkbox))
                    )->inputWidth(20)->required($val['is_required'], 'array');
                    break;

                case 'checkboxTable' : // 复选框 连表查询
                    $formParams = explode("\n", $val['form_params']);
                    $checkbox = [];
                    $model = new Content($formParams[0]);
                    $list = $model->get()->toArray();
                    foreach ($list as $k => $v) {
                        $checkbox[] = Checkbox::make($v[$formParams[1]], $v[$formParams[2]]);
                    }
                    $obj->component(
                        CheckboxGroup::make()->options($checkbox)->min(0)->max(count($list))
                    )->inputWidth(20)->required($val['is_required'], 'array');
                    break;

                case 'textArea' : // 长文本
                    $obj->component(Input::make()->textarea(4)->showWordLimit())->inputWidth(15)->required($val['is_required'], 'string');
                    break;

                case 'upload' : // 图片上传 单图
                    $obj->component(Upload::make()->image()->uniqueName())->required($val['is_required'], 'string');
                    break;

                case 'avatar' : // 头像
                    $obj->component(Upload::make()->avatar()->path('avatar')->uniqueName())->required($val['is_required'], 'string');
                    break;
                case 'uploadMulti' : // 图片上传 多图
                    $obj->component(
                        Upload::make()->image()->multiple()->uniqueName()
                            ->limit((int)$val['form_params'])->drag()
                    )->inputWidth(12)->required($val['is_required'], 'array');
                    break;

                case 'uploadFile' : // 文件上传 单个
                    $obj->component(
                        Upload::make()->file()->path('file')
                    )->inputWidth(12)->required($val['is_required'], 'string');
                    break;

                case 'dateTime' : // 日期时间
                    $obj->component(DateTimePicker::make())->required($val['is_required'], 'string');
                    break;

                case 'date' : // 日期
                    $obj->component(DatePicker::make())->required($val['is_required'], 'string');
                    break;

                case 'switch' : // 开关
                    $obj->component(CSwitch::make())->required($val['is_required'], 'number');
                    break;

                case 'color' : // 颜色选择
                    $obj->component(ColorPicker::make())->required($val['is_required'], 'string');
                    break;

                case 'password' : // 密码
                    $obj->component(function () {
                        return Input::make()->password()->showPassword();
                    })->inputWidth(4)->required($val['is_required'], 'string');
                    break;

                case 'wangEditor' : //
                    $obj->component(
                        WangEditor::make()->uploadImgServer($this->uploadImages)->uploadFileName('file')->style('min-height:300px;')
                    )->inputWidth(20);
                    break;

                case 'timePicker' : // 时间选择
                    $obj->component(TimePicker::make()->pickerOptions(
                        ['start' => '00:00', 'step' => '00:30', 'end' => '24:00']
                    )->placeholder($val['form_comment']))->required($val['is_required'], 'string');
                    break;

                case 'timePickerRange' : // 时间段选择
                    $obj->component(TimePicker::make([])->pickerOptions(
                        ['start' => '00:00', 'step' => '01:00', 'end' => '24:00']
                    )->isRange()->rangeSeparator('至')->placeholder($val['form_comment']))->required($val['is_required'], 'string');
                    break;

                case 'selectRemote' : // 下拉单选远程查询
                    $editId = request()->route()->parameter('content');
                    $options = [];
                    if (!empty($editId)) {
                        $form->editData($editId);
                        $formInfo = $form->model()->toArray();
                        $value = $formInfo[$val['name']];

                        $formParams = !empty($val['form_params']) ? explode("\n", $val['form_params']) : '';
                        $val['table_where'] = $formParams[1] . ",=," . $value;

                        $options = $this->_selectOptionTable($val);
                    }

                    $obj->component(
                        Select::make()->filterable()->remote($this->remoteUrl)->options($options)
                            ->extUrlParams(['val' => $val])
                            ->paginate(10)->clearable()->placeholder('请输入查询')
                    )->inputWidth(10)->required($val['is_required'], 'number')
                        ->unique($isUnique, $this->entity->table_name, $val['name'], $val['comment']);
                    break;

                case 'hidden' : //隐藏框
                    $defaultValue = request('default_' . $val['name']);
                    $obj->component(
                        Input::make($defaultValue)->type('hidden')
                    )->hideLabel()->inputWidth(2);
                    break;

                case 'cascade' : // 级联单选
                    $childrenFields = EntityField::query()->find($val['children_id']);
                    $cascadeFields[$val['name']] = $childrenFields['name'];

                    $obj->component(
                        Cascader::make()->filterable()->options($this->_cascadeOptions($val))->clearable()
                    )->inputWidth(20)->required(true, 'array');
                    break;

                case 'cascadeMulti' : // 级联多选
                    $childrenFields = EntityField::query()->find($val['children_id']);
                    $cascadeFields[$val['name']] = $childrenFields['name'];

                    $obj->component(
                        Cascader::make()->filterable()->options($this->_cascadeOptions($val))->clearable()->multiple()->collapseTags()
                    )->inputWidth(20)->required(true, 'array');
                    break;

            }

        }

        $form = $this->form_add($form, $isEdit);

        // 操作人ID 隐藏显示
        $form->item('oper_user_id', '操作人')->component(
            Input::make(Admin::user()->id)->type('hidden')
        )->hideLabel()->inputWidth(2);

        $form->saved(function (Form $form) use ($isEdit) {
            return $this->saved_event($form);
        });

        $form->deleted(function (Form $form) {
            return $this->deleted_event($form);
        });

        $form->saving(function (Form $form) use ($cascadeFields) {
            // 级联选择拆分字段
            if (!empty($cascadeFields)) {
                foreach ($cascadeFields as $parentField => $childrenField) {
                    $newParentField = $parentField . '_id';

                    if (is_array($form->$parentField[0])) { // 多选
                        $newParentFieldVal = $childrenFieldVal = [];
                        foreach ($form->$parentField as $key => $val) {
                            $newParentFieldVal[] = '' . $val[0]; // 强制转换类型
                            $childrenFieldVal[] = '' . $val[1]; // 强制转换类型
                        }

                        $form->$newParentField = array_values(array_unique($newParentFieldVal));
                        $form->$childrenField = array_values(array_unique($childrenFieldVal));

                    } else { // 单选
                        $form->$newParentField = $form->$parentField[0];
                        $form->$childrenField = $form->$parentField[1];
                    }
                }

            }

            return $this->saving_event($form);
        });

        return $form;
    }

    // 列表回调
    protected function grid_list(Grid $grid)
    {
        return $grid;
    }

    // 操作栏回调
    protected function grid_action(Grid\Actions $actions)
    {

    }

    // 工具栏回调
    protected function grid_toolbars(Grid\Toolbars $toolbars)
    {

    }

    // 列表回调
    protected function form_add(Form $form, $isEdit)
    {
        return $form;
    }

    // 保存成功回调
    protected function saved_event(Form $form)
    {

    }

    // 删除回调
    protected function deleted_event(Form $form)
    {

    }

    // 保存之前回调
    protected function saving_event(Form $form)
    {

    }

    // 添加地址
    protected function create_url()
    {
        return $this->createUrl;
    }

    // 单选
    protected function _radio($fields)
    {
        $formParams = !empty($fields['form_params']) ? explode("\n", $fields['form_params']) : '';
        $type = !empty($fields['type']) ? $fields['type'] : 'integer';

        $return = [];
        if (!empty($formParams)) {
            foreach ($formParams as $k => &$v) {
                $v = explode('=', $v);
                if (!empty($v)) {
                    $v[0] = ($type == 'integer') ? (int)$v[0] : (string)$v[0];
                    $disabled = (isset($v[3]) && $v[3]) ? true : false;
                    $return[] = Radio::make($v[0], $v[1])->disabled($disabled);
                }
            }
        }

        return $return;
    }

    // 普通下拉单选 列表
    protected function _selectOptionList($fields)
    {
        $formParams = explode("\n", $fields['form_params']);
        $option = [];
        $type = [];
        foreach ($formParams as $k => &$v) {
            $v = explode('=', $v);
            $option[$v[0]] = $v[1];
            $type[$v[1]] = isset($v[2]) ? $v[2] : 'info';
        }

        return ['option' => $option, 'type' => $type];
    }

    // 普通下拉单选
    protected function _selectOption($fields, $value = 0, $label = 1)
    {
        $formParams = !empty($fields['form_params']) ? explode("\n", $fields['form_params']) : '';
        $type = !empty($fields['type']) ? $fields['type'] : 'integer';

        $return = [];
        if (!empty($formParams)) {
            foreach ($formParams as $k => &$v) {
                $v = explode('=', $v);
                if (!empty($v)) {
                    $v[$value] = ($type == 'integer') ? (int)$v[$value] : (string)$v[$value];
                    $disabled = (isset($v[3]) && $v[3]) ? true : false;
                    $return[] = SelectOption::make($v[$value], $v[$label])->disabled($disabled);
                }
            }
        }

        return $return;
    }

    // 连表下拉单选
    protected function _selectOptionTable($fields)
    {
        $formParams = !empty($fields['form_params']) ? explode("\n", $fields['form_params']) : '';
        $tableWhere = !empty($fields['table_where']) ? explode("\n", $fields['table_where']) : '';
        $type = !empty($fields['type']) ? $fields['type'] : 'integer';

        $desc = !empty($fields['desc']) ? $fields['desc'] : '';
        $descPrefix = !empty($fields['desc_prefix']) ? $fields['desc_prefix'] : '';
        $descSuffix = !empty($fields['desc_suffix']) ? $fields['desc_suffix'] : '';

        $return = [];
        if (!empty($formParams) && count($formParams) >= 3) {

            $model = new Content($formParams[0]);

            // 扩展下拉连表查询条件
            if (!empty($tableWhere)) {
                foreach ($tableWhere as $key => $val) {
                    $where = explode(',', $val);
                    $model = $model->where("$where[0]", "$where[1]", "$where[2]");
                }
            }
            $list = $model->get()->toArray();

            if (!empty($list)) {
                foreach ($list as $k => $v) {
                    $v[$formParams[1]] = ($type == 'integer') ? (int)$v[$formParams[1]] : (string)$v[$formParams[1]];

                    if (!empty($desc) && isset($v[$desc])) {
                        $return[] = SelectOption::make($v[$formParams[1]], $v[$formParams[2]])->desc($descPrefix . $v[$desc] . $descSuffix);
                    } else {
                        $return[] = SelectOption::make($v[$formParams[1]], $v[$formParams[2]]);
                    }
                }
            }
        }

        return $return;
    }

    // 连表下拉单选列表
    protected function _selectOptionTableList($fields)
    {
        $formParams = !empty($fields['form_params']) ? explode("\n", $fields['form_params']) : '';
        $tableWhere = !empty($fields['table_where']) ? explode("\n", $fields['table_where']) : '';
        $type = !empty($fields['type']) ? $fields['type'] : 'integer';

        $desc = !empty($fields['desc']) ? $fields['desc'] : '';
        $descPrefix = !empty($fields['desc_prefix']) ? $fields['desc_prefix'] : '';
        $descSuffix = !empty($fields['desc_suffix']) ? $fields['desc_suffix'] : '';

        $return = [];
        if (!empty($formParams) && count($formParams) >= 3) {

            $model = new Content($formParams[0]);

            // 扩展下拉连表查询条件
            if (!empty($tableWhere)) {
                foreach ($tableWhere as $key => $val) {
                    $where = explode(',', $val);
                    $model = $model->where("$where[0]", "$where[1]", "$where[2]");
                }
            }
            $list = $model->get()->toArray();

            if (!empty($list)) {
                foreach ($list as $k => $v) {
                    $v[$formParams[1]] = ($type == 'integer') ? (int)$v[$formParams[1]] : (string)$v[$formParams[1]];

                    if (!empty($desc) && isset($v[$desc])) {
                        $return[] = ['label' => $v[$formParams[2]], 'value' => $v[$formParams[1]], 'desc' => $descPrefix . $v[$desc] . $descSuffix];
                    } else {
                        $return[] = ['label' => $v[$formParams[2]], 'value' => $v[$formParams[1]]];
                    }

                }
            }
        }

        return $return;
    }

    // 是否显示表单
    public function isShow($val)
    {
        $isShow = true;
        if (!empty($val['show_where'])) {
            $showWhere = explode("\n", $val['show_where']);
            if (!empty($showWhere)) {
                foreach ($showWhere as $key => $val) {
                    $where = explode(',', $val);
                    $value = request($where[0]);
                    switch ($where[1]) {
                        case '==' :
                            if ($value == $where[2]) {
                                $isShow = true;
                            } else {
                                $isShow = false;
                            }
                            break;

                        case '<>' :
                            if ($value <> $where[2]) {
                                $isShow = true;
                            } else {
                                $isShow = false;
                            }
                            break;

                        case '>' :
                            if ($value > $where[2]) {
                                $isShow = true;
                            } else {
                                $isShow = false;
                            }
                            break;

                        case '>=' :
                            if ($value >= $where[2]) {
                                $isShow = true;
                            } else {
                                $isShow = false;
                            }
                            break;

                        case '<' :
                            if ($value < $where[2]) {
                                $isShow = true;
                            } else {
                                $isShow = false;
                            }
                            break;

                        case '<=' :
                            if ($value <= $where[2]) {
                                $isShow = true;
                            } else {
                                $isShow = false;
                            }
                            break;

                        case '!=' :
                            if ($value != $where[2]) {
                                $isShow = true;
                            } else {
                                $isShow = false;
                            }
                            break;
                    }
                }
            }
        }

        return $isShow;
    }

    // 级联选择
    protected function _cascadeOptions($fields, $parentId = 0)
    {
        $formParams = !empty($fields['form_params']) ? explode("\n", $fields['form_params']) : '';
        $tableWhere = !empty($fields['table_where']) ? explode("\n", $fields['table_where']) : '';

        $options = [];
        if (!empty($formParams) && count($formParams) >= 3) {

            $model = new Content($formParams[0]);

            // 扩展下拉连表查询条件
            if (!empty($tableWhere)) {
                foreach ($tableWhere as $key => $val) {
                    $where = explode(',', $val);
                    $model = $model->where("$where[0]", "$where[1]", "$where[2]");
                }
            }
            if (!empty($parentId)) {
                $model = $model->where("$formParams[3]", "=", $parentId);
            }

            $list = $model->get()->toArray();

            if (!empty($list)) {

                $childrenOptions = [];
                if (!empty($fields['children_id'])) {
                    $childrenFields = EntityField::query()->find($fields['children_id']);
                }

                foreach ($list as $k => $v) {
                    if (!empty($fields['children_id']) && !empty($childrenFields)) {
                        $childrenOptions = $this->_cascadeOptions($childrenFields, $v[$formParams[1]]);
                    }

                    $data = [
                        'value' => $v[$formParams[1]],
                        'label' => $v[$formParams[2]],
                    ];
                    if (!empty($childrenOptions)) {
                        $data['children'] = $childrenOptions;
                    }

                    $options[] = $data;
                }
            }
        }

        return $options;
    }

}
