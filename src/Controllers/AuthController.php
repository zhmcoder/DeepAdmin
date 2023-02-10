<?php

namespace Andruby\DeepAdmin\Controllers;

use Andruby\DeepAdmin\Validates\AdminUserValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Andruby\DeepAdmin\Facades\Admin;
use Andruby\DeepAdmin\Form;
use Andruby\DeepAdmin\Layout\Content;

class AuthController extends AdminController
{
    /**
     * @var string
     */
    protected $loginView = 'deep-admin::login';

    /**
     * Show the login page.
     *
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        if (config('deep_admin.https')) {
            app('request')->server->set('HTTPS', true);
        }

        $data = $this->vueData();

        $data['backgroundImage'] = config('deep_admin.login_background_image');
        $data['logoShow'] = config('deep_admin.logo_show');
        $data['logo'] = config('deep_admin.logo');
        $data['name'] = config('deep_admin.name');
        $data['desc'] = config('deep_admin.loginDesc');
        $data['auto_user'] = config('deep_admin.auto_user');

        $data['url']['postLogin'] = route('admin.post.login');

        return view($this->loginView, ['data' => $data]);
    }

    /**
     * Handle a login request.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        $this->loginValidator($request->all())->validate();

        $credentials = $request->only([$this->username(), 'password']);
        $remember = $request->get('remember', false);

        if ($this->guard()->attempt($credentials, $remember)) {
            return \Admin::responseRedirect(url($this->sendLoginResponse($request)), false, '登录成功', 'success', false);
        }

        return $this->responseError($this->getFailedLoginMessage());
    }

    /**
     * Get a validator for an incoming login request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function loginValidator(array $data)
    {
        return Validator::make($data, [
            $this->username() => 'required',
            'password' => 'required',
        ]);
    }

    /**
     * User logout.
     *
     * @return Redirect
     */
    public function getLogout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(config('deep_admin.route.prefix'));
    }

    /**
     * User setting page.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function getSetting(Content $content)
    {
        $form = $this->settingForm();
        $form->tools(
            function (Form\Tools $tools) {
                $tools->disableList();
                $tools->disableDelete();
                $tools->disableView();
            }
        );

        return $content
            ->title(trans('admin.user_setting'))
            ->body($form->edit(Admin::user()->id));
    }

    /**
     * Update user setting.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putSetting()
    {
        return $this->settingForm()->update(Admin::user()->id);
    }

    /**
     * Model-form for user setting.
     *
     * @return Form
     */
    protected function settingForm()
    {
        $class = config('deep_admin.database.users_model');

        $form = new Form(new $class());

        $form->display('username', trans('admin.username'));
        $form->text('name', trans('admin.name'))->rules('required');
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('confirmed|required');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->setAction(admin_url('auth/setting'));

        $form->ignore(['password_confirmation']);

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });

        $form->saved(function () {
            admin_toastr(trans('admin.update_succeeded'));

            return redirect(admin_url('auth/setting'));
        });

        return $form;
    }

    /**
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.failed')
            ? trans('auth.failed')
            : 'These credentials do not match our records.';
    }

    /**
     * Get the post login redirect path.
     *
     * @return string
     */
    protected function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : config('deep_admin.route.prefix');
    }


    protected function sendLoginResponse(Request $request)
    {
        admin_toastr(trans('admin.login_successful'));

        $request->session()->regenerate();

        return $this->redirectPath();
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function username()
    {
        return 'username';
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Admin::guard();
    }

    public function resetPwd(Request $request, AdminUserValidate $validate)
    {
        $validate_result = $validate->resetPwd($request->only(['password', 'password_confirm']));
        if ($validate_result) {

            $userId = \Admin::user()->id;
            $password = $request->input('password');

            $class = config('deep_admin.database.users_model');
            $result = $class::query()->where(['id' => $userId])->update(['password' => bcrypt($password)]);
            if ($result) {
                return \Admin::responseMessage("保存成功");
            } else {
                return \Admin::responseError('修改失败');
            }
        } else {
            return \Admin::responseError($validate->message);
        }
    }
}

