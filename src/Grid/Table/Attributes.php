<?php

namespace Andruby\DeepAdmin\Grid\Table;

use Illuminate\Support\Str;

class Attributes
{
    public $gridId = null;
    public $columnDataUrl = '';
    public $isColumn = false;

    public function __construct()
    {
        $this->border = env('TABLE_BORDER', false);
        $this->size = env('TABLE_SIZE', 'small');

        $this->gridId = strtolower(Str::random(8));
    }

    public $height;

    public $maxHeight;

    /**
     * @var bool
     */
    public $stripe = true;

    /**
     * @var bool
     */
    public $border = false;

    /**
     * @var string
     */
    public $size = "small";

    /**
     * @var bool
     */
    public $fit = true;

    /**
     * @var bool
     */
    public $showHeader = true;

    /**
     * @var bool
     */
    public $highlightCurrentRow = true;


    public $emptyText = '暂无数据';


    public $tooltipEffect;

    public $rowKey = 'id';


    public $draggable = false;
    public $draggableUrl;

    public $defaultExpandAll = false;

    public $remote = false;
    public $remoteUrl;

    public $treeProps = ['hasChildren' => 'hasChildren', 'children' => 'children'];


    public $hideActions = false;
    public $actionWidth;
    public $actionLabel = "操作";
    public $actionFixed;
    public $actionAlign = "left";

    public $selection = false;
    public $selectionFiled = null;
    public $selectionValue = [];//数组

    public $dataVuex = false;

    public $showSummary = false;
    public $topTool = true; // 是否显示顶部工具

    public $spanColumns = []; // 合并字段

}
