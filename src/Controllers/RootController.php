<?php


namespace Andruby\DeepAdmin\Controllers;

use Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class RootController extends Controller
{
    public function index()
    {
        $data = [
            'isLocal' => config('app.env') == "local",
            'menu' => Admin::menu(),
            'menuList' => Admin::menuList(),
            'logoShow' => config('deep_admin.logo_show'),
            'logo' => config('deep_admin.logo'),
            'logoLight' => config('deep_admin.logo_light'),
            'logoMini' => config('deep_admin.logo_mini'),
            'logoMiniLight' => config('deep_admin.logo_mini_light'),
            'name' => config('deep_admin.name'),
            'copyright' => config('deep_admin.copyright'),
            'footerLinks' => config('deep_admin.footerLinks'),
            'loginLinks' => config('deep_admin.loginLinks'),
            'uniqueOpened' => config('deep_admin.unique_opened', false),
            'user' => $this->getUserData(),
            'url' => $this->getUrls(),
            'resetPwdUrl' => route('auth.resetPwd'),
        ];

        return view('deep-admin::root', ['data' => $data]);
    }


    protected function getUserData()
    {
        if (!$user = Admin::user()) {
            return [];
        }
        return Arr::only($user->toArray(), ['id', 'username', 'email', 'name', 'avatar']);
    }

    protected function getUrls()
    {
        return [
            'logout' => route('admin.logout'),
            'setting' => route('admin.setting')
        ];
    }
}
