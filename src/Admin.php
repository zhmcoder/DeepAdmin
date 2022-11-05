<?php

namespace Andruby\DeepAdmin;

use Illuminate\Support\Facades\Auth;
use Andruby\DeepAdmin\Auth\Database\Administrator;
use Andruby\DeepAdmin\Auth\Database\Menu;

class Admin
{

    protected $menu = [];
    protected $menuList = [];
    public static $metaTitle;

    public static $scripts = [];

    public static $styles = [];

    public static $css = [];

    public static function css($css = null)
    {
        if (!is_null($css)) {
            return self::$css = array_merge(self::$css, (array)$css);
        }
        $css = array_merge(static::$css, []);
        $css = array_filter(array_unique($css));
        return view('deep-admin::partials.css', compact('css'));
    }


    public static function script($name, $path)
    {
        static::$scripts[$name] = $path;

        return new static;
    }

    public static function style($name, $path)
    {
        static::$styles[$name] = $path;

        return new static;
    }

    public static function scripts()
    {
        return static::$scripts;
    }

    public static function styles()
    {
        return static::$styles;
    }

    public static function setTitle($title)
    {
        self::$metaTitle = $title;
    }

    /**
     * Get admin title.
     *
     * @return string
     */
    public function title()
    {
        return self::$metaTitle ? self::$metaTitle : config('deep_admin.title');
    }

    public function menu()
    {
        if (!empty($this->menu)) {
            return $this->menu;
        }
        $menuClass = config('deep_admin.database.menu_model');
        /** @var Menu $menuModel */
        $menuModel = new $menuClass();
        $allNodes = $menuModel->allNodes();
        return $this->menu = $menuModel->buildNestedArray($allNodes);
    }

    public function menuList()
    {
        if (!empty($this->menuList)) {
            return $this->menuList;
        }
        $menuClass = config('deep_admin.database.menu_model');
        /** @var Menu $menuModel */
        $menuModel = new $menuClass();
        return $this->menuList = collect($menuModel->allNodes())->map(function ($item) {
            return [
                'uri' => $item['uri'],
                'title' => $item['title'],
                'route' => $item['route'],
            ];
        })->all();


    }


    public function bootstrap()
    {
        require config('deep_admin.bootstrap', admin_path('bootstrap.php'));
    }


    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null|Administrator
     */
    public function user()
    {
        return $this->guard()->user();
    }

    /**
     * Attempt to get the guard from the local cache.
     *
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard()
    {
        $guard = config('deep_admin.auth.guard') ?: 'admin';

        return Auth::guard($guard);
    }


    public function validatorData(array $all, $rules, $message = [])
    {
        $validator = \Validator::make($all, $rules, $message);
        if ($validator->fails()) {
            abort(400, $validator->errors()->first());
        }
        return $validator;
    }

    public function response($data, $message = '', $code = 200, $headers = [])
    {

        $re_data = [
            'code' => $code,
            'message' => $message,
        ];
        if ($data) {
            $re_data['data'] = $data;
        }
        return \Response::json($re_data, 200, $headers);
    }


    public function responseMessage($message = '', $code = 200)
    {
        return $this->response([], $message, $code);
    }

    public function responseError($message = '', $code = 400)
    {
        return $this->response([], $message, $code);
    }

    /**
     * @param $url
     * @param bool $isVueRoute
     * @param string $message
     * @param string $type info/success/warning/error
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseRedirect($url, $isVueRoute = true, $message = null, $type = 'success')
    {
        $urlArr = explode('?', $url);
        if (!empty($urlArr[1])) {
            $url .= '&version=' . time();
        } else {
            $url .= '?version=' . time();
        }

        return $this->response([
            'url' => $url,
            'isVueRoute' => $isVueRoute,
            'type' => $type
        ], $message, 301);
    }
}
