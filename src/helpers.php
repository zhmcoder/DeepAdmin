<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Andruby\DeepAdmin\Components\Widgets\Html;
use Andruby\DeepAdmin\Layout\Content;

if (!function_exists('debug_info')) {
    function debug_log_info($message, array $context = array())
    {
        Log::channel('debug_log')->info($message, $context);
    }
}


if (!function_exists('admin_path')) {
    /**
     * Get admin path.
     * @param string $path
     * @return string
     */
    function admin_path(string $path = ''): string
    {
        return ucfirst(config('deep_admin.directory')) . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('admin_base_path')) {
    /**
     * Get admin url.
     * @param string $path
     * @return string
     */
    function admin_base_path(string $path = ''): string
    {
        $prefix = '/' . trim(config('deep_admin.route.prefix'), '/');
        $prefix = ($prefix == '/') ? '' : $prefix;
        $path = trim($path, '/');

        if (is_null($path) || strlen($path) == 0) {
            return $prefix ?: '/';
        }

        return $prefix . '/' . $path;
    }
}

if (!function_exists('admin_api_base_path')) {
    /**
     * Get admin url.
     * @param string $path
     * @return string
     */
    function admin_api_base_path(string $path = ''): string
    {
        $prefix = '/' . trim(config('deep_admin.route.prefix_api'), '/');
        $prefix = ($prefix == '/') ? '' : $prefix;
        $path = trim($path, '/');

        if (is_null($path) || strlen($path) == 0) {
            return $prefix ?: '/';
        }

        return $prefix . '/' . $path;
    }
}

if (!function_exists('admin_url')) {
    /**
     * Get admin url.
     * @param string $path
     * @param mixed $parameters
     * @param bool|null $secure
     * @return string
     */
    function admin_url(string $path = '', $parameters = [], bool $secure = null): string
    {
        if (\Illuminate\Support\Facades\URL::isValidUrl($path)) {
            return $path;
        }
        $secure = $secure ?: (config('deep_admin.https') || config('deep_admin.secure'));
        return url(admin_base_path($path), $parameters, $secure);
    }
}

if (!function_exists('admin_api_url')) {
    /**
     * Get admin url.
     * @param string $path
     * @param mixed $parameters
     * @param bool|null $secure
     * @return string
     */
    function admin_api_url(string $path = '', $parameters = [], bool $secure = null): string
    {
        if (\Illuminate\Support\Facades\URL::isValidUrl($path)) {
            return $path;
        }
        $secure = $secure ?: (config('deep_admin.https') || config('deep_admin.secure'));
        return url(admin_api_base_path($path), $parameters, $secure);
    }
}

if (!function_exists('admin_file_url')) {
    function admin_file_url($path)
    {
        if (\Illuminate\Support\Str::contains($path, "//")) {
            return $path;
        }

        return \Storage::disk(config('deep_admin.upload.disk'))->url($path);
    }
};

if (!function_exists('admin_toastr')) {
    /**
     * Flash a toastr message bag to session.
     * @param string $message
     * @param string $type
     * @param array $options
     */
    function admin_toastr(string $message = '', string $type = 'success', array $options = [])
    {
        $toastr = new MessageBag(get_defined_vars());

        session()->flash('toastr', $toastr);
    }
}

if (!function_exists('admin_asset')) {
    /**
     * @param $path
     * @return string
     */
    function admin_asset($path): string
    {
        return (config('deep_admin.https') || config('deep_admin.secure')) ? secure_asset($path) : asset($path);
    }
}

if (!function_exists('instance_content')) {
    function instance_content($content = '')
    {
        if (is_string($content)) {
            return Html::make()->html($content);
        } elseif ($content instanceof \Closure) {
            $c_content = new Content();
            call_user_func($content, $c_content);
            return $c_content;
        } else {
            return $content;
        }
    }
}

// 从一个标准url里取出文件的扩展名
if (!function_exists('getFileExt')) {
    function getFileExt($url)
    {
        $arr = parse_url($url);
        $file = basename($arr['path']);
        $ext = explode('.', $file);

        return $ext[count($ext) - 1] ?? '';
    }
}
