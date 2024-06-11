<?php

namespace Andruby\DeepAdmin\Validates;

class AdminUserValidate extends Validate
{
    public function resetPwd($request_data)
    {
        $rules = [
            'password_confirm' => 'required|same:password|min:6',
            'password' => 'required|min:6',
        ];
        $message = [
            'password.required' => '密码不能为空',
            'password.min' => '密码长度至少为6个字符',
            'password_confirm.required' => '确认密码不能为空',
            'password_confirm.same' => '密码与确认密码不一致',
            'password_confirm.min' => '密码长度至少为6个字符',
        ];
        return $this->validate($request_data, $rules, $message);
    }
}
