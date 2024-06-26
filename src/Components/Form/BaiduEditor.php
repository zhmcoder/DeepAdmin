<?php


namespace Andruby\DeepAdmin\Components\Form;


use Andruby\DeepAdmin\Components\Component;

class BaiduEditor extends Component
{

    protected $componentName = 'BaiduEditor';

    protected $menus = [
        'head',  // 标题
        'bold',  // 粗体
        'fontSize',  // 字号
        'fontName',  // 字体
        'italic',  // 斜体
        'underline',  // 下划线
        'strikeThrough',  // 删除线
        'foreColor',  // 文字颜色
        'backColor',  // 背景颜色
        'link',  // 插入链接
        'list',  // 列表
        'justify',  // 对齐方式
        'quote',  // 引用
        'emoticon',  // 表情
        'image',  // 插入图片
        'table',  // 表格
        'video',  // 插入视频
        'code',  // 插入代码
        'undo',  // 撤销
        'redo'  // 重复
    ];

    protected $zIndex = 0;

    protected $uploadImgShowBase64 = false;

    protected $uploadImgServer;

    protected $uploadFileName;

    protected $uploadImgHeaders;

    protected $jsBasePath;//百度编辑器基础js, 必传

    protected $component;

    protected $disabled = false;

    static public function make($value = null)
    {
        $obj = new BaiduEditor($value);
        $obj->jsBasePath = config("deep_admin.baidu_editor.js_base_path");//实例化组件自动设置
        return $obj;
    }

    /**
     * 自定义菜单
     * @param array $menus
     * @return $this
     */
    public function menus(array $menus)
    {
        $this->menus = $menus;
        return $this;
    }

    /**
     * 编辑区域的 z-index
     * @param int $zIndex
     * @return $this
     */
    public function zIndex(int $zIndex)
    {
        $this->zIndex = $zIndex;
        return $this;
    }

    /**
     * 使用 base64 保存图片
     * @param bool $uploadImgShowBase64
     * @return $this
     */
    public function uploadImgShowBase64(bool $uploadImgShowBase64 = true)
    {
        $this->uploadImgShowBase64 = $uploadImgShowBase64;
        return $this;
    }

    /**
     * 配置服务器端地址
     * @param string $uploadImgServer
     * @return $this
     */
    public function uploadImgServer(string $uploadImgServer)
    {
        $this->uploadImgServer = $uploadImgServer;
        return $this;
    }

    /**
     * 自定义 header
     * @param mixed $uploadImgHeaders
     * @return WangEditor
     */
    public function uploadImgHeaders(array $uploadImgHeaders)
    {
        $this->uploadImgHeaders = $uploadImgHeaders;
        return $this;
    }

    /**
     * @param mixed $component
     * @return WangEditor
     */
    public function component($component)
    {
        $this->component = $component;
        return $this;
    }


    /**
     * 是否禁用
     * @param bool $disabled
     * @return WangEditor
     */
    public function disabled($disabled = true)
    {
        $this->disabled = $disabled;
        return $this;
    }

}
