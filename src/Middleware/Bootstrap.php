<?php

namespace Andruby\DeepAdmin\Middleware;

use Closure;
use Andruby\DeepAdmin\Facades\Admin;
use Illuminate\Http\Request;

class Bootstrap
{
    public function handle(Request $request, Closure $next)
    {
        Admin::bootstrap();

        return $next($request);
    }
}
