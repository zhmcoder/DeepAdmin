<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('debug_info')) {


    function debug_log_info($message, array $context = array())
    {
        Log::channel('debug_log')->info($message, $context);
    }
}
