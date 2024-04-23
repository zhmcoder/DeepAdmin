<?php


namespace Andruby\DeepAdmin\Components\Form;

use Illuminate\Support\Arr;
use Andruby\DeepAdmin\Components\Component;
use Andruby\DeepAdmin\Form;
use Andruby\DeepAdmin\Form\FormItem;
use Storage;

class UploadList extends Component
{
    protected $componentName = "UploadList";

    protected $type = "image";
    protected $action = "";
    protected $host = "";
    protected $multiple = false;
    protected $data = [];
    protected $showFileList = false;
    protected $drag = false;
    protected $accept;
    protected $listType = 'text';
    protected $disabled = false;
    protected $limit = 1;

    protected $width = 100;
    protected $height = 100;


    protected $keyName;
    protected $valueName;

    protected $remove_flag_name = Form::REMOVE_FLAG_NAME;

    protected $showProgress = true;

    public function __construct($value = null)
    {
        ##deep admin start
        $this->action = route(config('deep_admin.upload.image_handle_router', 'admin.handle-upload-image'));
        ##deep admin end
        $this->host = \Storage::disk(config('deep_admin.upload.disk'))->url('/');
        $this->componentValue($value);
    }

    static public function make($value = null)
    {
        return new UploadList($value);
    }

    public function destroy(FormItem $formItem)
    {
        $files = [];

        if (is_array($formItem->original)) {
            $files = $formItem->original;
        } else {
            $files[] = $formItem->original;
        }
        $storage = Storage::disk(config('deep_admin.upload.disk'));
        collect($files)->each(function ($file) use ($storage) {

            if (!empty($this->valueName)) {
                $file = $file[$this->valueName];
            }
            if ($storage->exists($file)) {
                $storage->delete($file);
            }
        });
    }

    /**
     * @param string $action
     * @return $this
     */
    public function action($action)
    {
        $this->action = $action;
        return $this;
    }


    /**
     * @param string $host
     * @return $this;
     */
    public function host(string $host)
    {
        $this->host = $host;
        return $this;
    }


    /**
     * @param bool $multiple
     * @param null|string $keyName
     * @param null|string $valueName
     * @return $this
     */
    public function multiple(bool $multiple = true, $keyName = null, $valueName = null)
    {
        $this->multiple = $multiple;
        $this->keyName = $keyName;
        $this->valueName = $valueName;
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function data($data)
    {

        foreach ($data as $key => $value) {
            $this->data = Arr::set($this->data, $key, $value);
        }

        return $this;
    }

    /**
     * 文件保存目录
     * @param $path
     * @return $this
     */
    public function path($path)
    {
        $this->data = Arr::set($this->data, "path", $path);
        return $this;
    }

    /**
     * 自动生成文件名
     * @return $this
     */
    public function uniqueName($uniqueName = true)
    {
        $this->data = Arr::set($this->data, "uniqueName", $uniqueName);
        return $this;
    }

    /**
     * @param bool $drag
     * @return $this
     */
    public function drag(bool $drag = true)
    {
        $this->drag = $drag;
        return $this;
    }

    /**
     * @param string $accept
     * @return $this
     */
    public function accept($accept)
    {
        $this->accept = $accept;
        return $this;
    }


    /**
     * @param bool $disabled
     * @return $this
     */
    public function disabled(bool $disabled = true)
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return $this;
     */
    public function image($disk = 'public')
    {
        $this->type = "image";
        $this->action = route(config('deep_admin.upload.image_handle_router', 'admin.handle-upload-image'),
            ['disk' => $disk]);
        $this->accept = config('deep_admin.upload.image');
        $this->host = \Storage::disk($disk)->url('/');
        return $this;
    }

    public function avatar($disk = 'public')
    {
        $this->type = "avatar";
        $this->action = route(config('deep_admin.upload.image_handle_router', 'admin.handle-upload-image'),
            ['disk' => $disk]);
        $this->accept = config('deep_admin.upload.avatar');
        $this->host = \Storage::disk($disk)->url('/');
        return $this;
    }

    public function file($disk = 'public')
    {
        $this->type = "file";
        ##deep admin start
        $this->action = route(config('deep_admin.upload.file_handle_router', 'admin.handle-upload-file'),
            ['disk' => $disk]);
        $this->accept = config('deep_admin.upload.file');
        $this->host = \Storage::disk($disk)->url('/');
        ##deep admin end
        return $this;
    }

    public function xlsx($disk = 'public')
    {
        $this->type = "xlsx";
        $this->action = route(config('deep_admin.upload.xlsx_handle_router', 'admin.handle-upload-xlsx'),
            ['disk' => $disk]);
        $this->accept = config('deep_admin.upload.xlsx');
        $this->host = \Storage::disk($disk)->url('/');
        return $this;
    }

    /**
     * @param int $width
     * @return $this
     */
    public function width(int $width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param int $height
     * @return $this
     */
    public function height(int $height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param float $showProgress
     * @return $this
     */
    public function showProgress($showProgress = true)
    {
        $this->showProgress = $showProgress;
        return $this;
    }

}
