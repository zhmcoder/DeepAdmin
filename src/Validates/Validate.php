<?php

namespace Andruby\DeepAdmin\Validates;

use Illuminate\Support\Facades\Validator;

class Validate
{
    public $message = null;

    protected function validate($request_data, $rules, $message)
    {
        $validator = Validator::make($request_data, $rules, $message);
        if ($validator->fails()) {
            $this->message = $validator->errors()->first();;
            return false;
        }
        return true;
    }
}
