<?php


namespace Andruby\DeepAdmin;


use Andruby\DeepAdmin\Grid\Concerns\HasTabFilter;
use Andruby\DeepAdmin\Grid\Concerns\HasTotalData;
use Andruby\DeepAdmin\Grid\RightFilter;
use Andruby\DeepAdmin\Grid\TreeFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Str;
use Andruby\DeepAdmin\Components\Component;
use Andruby\DeepAdmin\Grid\Actions;
use Andruby\DeepAdmin\Grid\BatchActions;
use Andruby\DeepAdmin\Grid\Column;
use Andruby\DeepAdmin\Grid\Concerns\HasDefaultSort;
use Andruby\DeepAdmin\Grid\Concerns\HasFilter;
use Andruby\DeepAdmin\Grid\Concerns\HasGridAttributes;
use Andruby\DeepAdmin\Grid\Concerns\HasPageAttributes;
use Andruby\DeepAdmin\Grid\Concerns\HasQuickFilter;
use Andruby\DeepAdmin\Grid\Concerns\HasQuickSearch;
use Andruby\DeepAdmin\Grid\Filter;
use Andruby\DeepAdmin\Grid\LeftFilter;
use Andruby\DeepAdmin\Grid\Model;
use Andruby\DeepAdmin\Grid\Table\Attributes;
use Andruby\DeepAdmin\Grid\Toolbars;
use Andruby\DeepAdmin\Layout\Content;


class Grid extends Component
{
    use HasGridAttributes, HasPageAttributes, HasDefaultSort, HasQuickSearch, HasFilter, HasQuickFilter, HasTabFilter, HasTotalData;

    //<!--deep admin-->
    /**
     * 组件名称
     * @var string
     */
    protected $componentName = 'Grid';
    /**
     * 组件模型
     * @var Model
     */
    protected $model;
    /**
     * 组件字段
     * @var Column[]
     */
    protected $columns = [];
    protected $rows;
    /**
     * 组件字段属性
     * @var array
     */
    protected $columnAttributes = [];
    /**
     * @var string
     */
    protected $keyName = 'id';
    /**
     * @var bool
     */
    protected $tree = false;
    /**
     * 表格数据来源
     * @var string
     */
    protected $dataUrl;

    protected $customData;

    protected $isGetData = false;
    private $actions;
    private $batchActions;
    private $toolbars;
    private $top;
    private $bottom;
    private $isReload = false;

    /**
     * 请求方式
     * @var string
     */
    private $method = "get";

    /**
     * 附加字段
     * @var array
     */
    private $appendFields = [];


    /**
     * @var Form
     */
    protected $dialogForm;
    protected $isDialogForm;
    protected $isDrawerForm;
    protected $dialogFormWidth;
    protected $dialogTitle = ['添加', '修改'];

    /**
     * @var Form
     */
    protected $addDialogForm;
    protected $addDialogFormWidth;
    protected $addDialogFormTitle = ['添加', '修改'];

    /**
     * @var Form
     */
    protected $editDialogForm;
    protected $editDialogFormWidth;
    protected $editDialogFormTitle = ['添加', '修改'];

    /*
     * 弹窗标题居中
     */
    protected $dialogTitleCenter = false;

    protected $filterSearchLabel = '搜索';
    protected $filterResetLabel = '重置';

    protected $isMultiple = false; // 是否多选（false单选、true多选）


    /**
     * 居中显示
     * @var bool
     */
    protected $filterFormCenter;

    public function __construct(Eloquent $model = null)
    {
        $this->perPage = env('PER_PAGE', 15);
        $this->pageSizes = env('PAGE_SIZES', [10, 15, 20, 30, 50, 100]);

        $this->attributes = new Attributes();
        $this->dataUrl = admin_api_url(request()->path());
        $this->model = new Model($model, $this);
        if ($model) {
            $this->keyName = $model->getKeyName();
            $this->defaultSort($model->getKeyName(), "desc");
        }
        $this->isGetData = request('get_data') == "true";
        $this->toolbars = new Toolbars($this);
        $this->batchActions = new BatchActions();
        $this->filter = new Filter($this->model);
        $this->leftFilter = new LeftFilter($this->model);
        $this->rightFilter = new RightFilter($this->model);
        $this->treeFilter = new TreeFilter($this->model);
    }

    public function getLeftFilter(): array
    {
        return $this->leftFilter->buildFilter();
    }

    public function getRightFilter(): array
    {
        return $this->rightFilter->buildFilter();
    }

    public function getTreeFilter(): array
    {
        return $this->treeFilter->buildFilter();
    }

    /**
     * 获取自定义数据模型
     * @return Model|Builder
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getKeyName(): string
    {
        return $this->keyName;
    }

    /**
     * 自定义数据源路径
     * @param string $dataUrl
     * @return $this
     */
    public function dataUrl(string $dataUrl)
    {
        $this->dataUrl = $dataUrl;
        return $this;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function method(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return array
     */
    public function getAppendFields(): array
    {
        return $this->appendFields;
    }

    /**
     * 数据返回附加字段
     * @param array $appendFields
     * @return $this
     */
    public function appendFields(array $appendFields)
    {
        $this->appendFields = $appendFields;
        return $this;
    }


    /**
     * @return bool
     */
    public function isGetData(): bool
    {
        return $this->isGetData;
    }

    public function setIsGetData($isGetData)
    {
        $this->isGetData = $isGetData;
        return $this;
    }


    /**
     * 设置树形表格
     * @param bool $tree
     * @return $this
     */
    public function tree($tree = true)
    {
        $this->tree = $tree;
        return $this;
    }


    /**
     * Grid添加字段
     * @param string $name 对应列内容的字段名
     * @param string $label 显示的标题
     * @param string $columnKey 排序查询等数据操作字段名称
     * @return Column
     */
    public function column($name, $label = '', $columnKey = null)
    {
        if (Str::contains($name, '.')) {
            $this->addRelationColumn($name, $label);
        }

        return $this->addColumn($name, $label, $columnKey);
    }

    /**
     * @param string $name
     * @param string $label
     * @param $columnKey
     * @return Column
     */
    protected function addColumn($name = '', $label = '', $columnKey = null)
    {
        $column = new Column($name, $label, $columnKey);
        $column->setGrid($this);
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Add a relation column to grid.
     *
     * @param string $name
     * @param string $label
     *
     * @return $this|bool|Column
     */
    protected function addRelationColumn($name, $label = '')
    {
        if ($this->model) {
            list($relation, $column) = explode('.', $name);
            $model = $this->model()->eloquent();
            if (!method_exists($model, $relation) || !$model->{$relation}() instanceof Relations\Relation) {
            } else {
                $this->model()->with($relation);
            }

        }

    }

    /**
     * @param Column[] $columns
     */
    protected function columns($columns)
    {
        $this->columnAttributes = collect($columns)->map(function (Column $column) {
            return $column->getAttributes();
        })->toArray();
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getColumnAttributes()
    {
        return $this->columnAttributes;
    }

    protected function applyQuery()
    {
        //快捷搜索
        $this->applyQuickSearch();
        //<!--deep admin start-->
        $this->applyQuickFilter();
        $this->applyTabFilter();
        //<!--deep admin end-->
        $this->applyFilter(false);

    }

    /**
     * 自定义toolbars
     * @param $closure
     * @return $this
     */
    public function toolbars($closure)
    {
        call_user_func($closure, $this->toolbars);
        return $this;
    }

    /**
     * 自定义行操作
     * @param $closure
     * @return $this
     */
    public function actions($closure)
    {
        $this->actions = $closure;
        return $this;
    }

    /**
     * 自定义批量操作
     * @param \Closure $closure
     * @return $this
     */
    public function batchActions(\Closure $closure)
    {
        call_user_func($closure, $this->batchActions);
        return $this;
    }

    /**
     * 获取行操作
     * @param $row
     * @param $key
     * @return mixed
     */
    public function getActions($row, $key)
    {
        $actions = new Actions($this);
        $actions->row($row)->key($key);
        if ($this->actions) call_user_func($this->actions, $actions);
        return $actions->builderActions();
    }

    /**
     * @param Form $dialogForm
     * @param  $width
     * @param  $title
     * @return Grid
     */
    public function dialogForm(Form $dialogForm, $width = '500px', $title = ['添加', '修改'])
    {
        $this->dialogForm = $dialogForm;
        $this->dialogFormWidth = $width;
        $this->dialogTitle = $title;
        $this->isDialogForm = true;
        return $this;
    }

    public function isDrawerForm()
    {
        $this->isDrawerForm = true;
        return $this;
    }

    /**
     * @param Form $dialogForm
     * @param  $width
     * @param  $title
     * @return Grid
     */
    public function addDialogForm(Form $dialogForm, $width = '500px', $title = ['添加', '编辑'])
    {
        $this->addDialogForm = $dialogForm;
        $this->addDialogFormWidth = $width;
        $this->addDialogFormTitle = $title;
        $this->isDialogForm = true;
        return $this;
    }

    /**
     * @param Form $dialogForm
     * @param  $width
     * @param  $title
     * @return Grid
     */
    public function editDialogForm(Form $dialogForm, $width = '500px', $title = ['添加', '编辑'])
    {
        $this->editDialogForm = $dialogForm;
        $this->editDialogFormWidth = $width;
        $this->editDialogFormTitle = $title;
        $this->isDialogForm = true;
        return $this;
    }

    /**
     * @return Form
     */
    public function getDialogForm()
    {
        return $this->dialogForm;
    }

    public function getAddDialogForm()
    {
        return $this->addDialogForm;
    }

    public function getEditDialogForm()
    {
        return $this->editDialogForm;
    }

    /**
     * 搜索居中
     *
     * @param bool $filterFormCenter
     * @return $this
     */
    public function filterFormCenter($filterFormCenter = true)
    {
        $this->filterFormCenter = $filterFormCenter;
        return $this;
    }

    /**
     * 弹窗标题居中
     *
     * @param bool $dialogTitleCenter
     * @return $this
     */
    public function dialogTitleCenter($dialogTitleCenter = true)
    {
        $this->dialogTitleCenter = $dialogTitleCenter;
        return $this;
    }

    public function filterSearchLabel($filterSearchLabel = '搜索')
    {
        $this->filterSearchLabel = $filterSearchLabel;
        return $this;
    }

    public function filterResetLabel($filterResetLabel = '重置')
    {
        $this->filterResetLabel = $filterResetLabel;
        return $this;
    }

    /**
     * @param $closure
     * @return $this
     */
    public function top($closure)
    {
        $this->top = new Content();
        call_user_func($closure, $this->top);
        return $this;
    }

    /**
     * @param $closure
     * @return $this
     */
    public function bottom($closure)
    {
        $this->bottom = new Content();
        call_user_func($closure, $this->bottom);
        return $this;
    }

    /**
     * @param bool $isReload
     * @return $this
     */
    public function isReload(bool $isReload = true)
    {
        $this->isReload = $isReload;
        return $this;
    }


    /**
     * 自定义数据
     * @param $data
     * @param int $current_page
     * @param int $per_page
     * @param int $last_page
     * @param int $total
     * @param array $total_data
     * @return $this
     */
    public function customData($data, $current_page = 0, $per_page = 0, $last_page = 0, $total = 0, $total_data = [])
    {
        $this->customData = [
            'current_page' => (int)$current_page,
            'per_page' => (int)$per_page,
            'last_page' => (int)$last_page,
            'total' => (int)$total,
            'data' => $data,
            'total_data' => $total_data,
        ];
        return $this;
    }


    /**
     * data
     * @return array
     */
    public function data(): array
    {
        if ($this->customData) {
            $this->customData['data'] = $this->model()->displayData($this->customData['data']);
            return [
                'code' => 200,
                'data' => $this->customData
            ];
        }

        $this->applyQuery();

        $data = $this->model->buildData();

        return [
            'code' => 200,
            'data' => $data
        ];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        if (count($this->columnAttributes) <= 0) {
            $this->columns($this->columns);
        }
        if ($this->isGetData) {
            return $this->data();
        } else {
            $viewData['componentName'] = $this->componentName;
            $viewData['routers'] = [
                'resource' => admin_api_url(request()->path()),
            ];
            $viewData['keyName'] = $this->keyName;
            $viewData['selection'] = $this->attributes->selection;
            $viewData['tree'] = $this->tree;
            $viewData['defaultSort'] = $this->defaultSort;
            $viewData['columnAttributes'] = $this->columnAttributes;
            $viewData['attributes'] = (array)$this->attributes;
            $viewData['dataUrl'] = $this->dataUrl;
            $viewData['method'] = $this->method;
            $viewData['hidePage'] = $this->isHidePage();
            $viewData['pageSizes'] = $this->pageSizes;
            $viewData['perPage'] = $this->perPage;
            $viewData['pageLayout'] = $this->pageLayout;
            $viewData['pageBackground'] = $this->pageBackground;
            $viewData['toolbars'] = $this->toolbars->builderData();
            $viewData['batchActions'] = $this->batchActions->builderActions();
            $viewData['quickSearch'] = $this->quickSearch;
            //<!--deep admin start-->
            $viewData['quickFilter'] = $this->quickFilter;
            $viewData['tabFilter'] = $this->tabFilter;
            //<!--deep admin end-->
            $viewData['filter'] = $this->filter->buildFilter();
            $viewData['leftFilter'] = $this->leftFilter->buildFilter();
            $viewData['rightFilter'] = $this->rightFilter->buildFilter();
            $viewData['treeFilter'] = $this->treeFilter->buildFilter();
            $viewData['top'] = $this->top;
            $viewData['bottom'] = $this->bottom;
            $viewData['isReload'] = $this->isReload;

            $viewData['isDialogForm'] = $this->isDialogForm;
            $viewData['isDrawerForm'] = $this->isDrawerForm;
            $viewData['dialogForm'] = $this->dialogForm;
            $viewData['dialogFormWidth'] = $this->dialogFormWidth;
            $viewData['dialogTitle'] = $this->dialogTitle;

            $viewData['addDialogForm'] = $this->addDialogForm;
            $viewData['addDialogFormWidth'] = $this->addDialogFormWidth;
            $viewData['addDialogFormTitle'] = $this->addDialogFormTitle;

            $viewData['editDialogForm'] = $this->editDialogForm;
            $viewData['editDialogFormWidth'] = $this->editDialogFormWidth;
            $viewData['editDialogFormTitle'] = $this->editDialogFormTitle;

            $viewData['ref'] = $this->getRef();
            $viewData['filterFormCenter'] = $this->filterFormCenter;
            $viewData['dialogTitleCenter'] = $this->dialogTitleCenter;

            $viewData['filterSearchLabel'] = $this->filterSearchLabel;
            $viewData['filterResetLabel'] = $this->filterResetLabel;

            $viewData['isMultiple'] = $this->isMultiple;

            return $viewData;
        }
    }
}
