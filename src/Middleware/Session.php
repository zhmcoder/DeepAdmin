<?php

namespace Andruby\DeepAdmin\Middleware;

use Illuminate\Http\Request;

class Session
{
    public function handle(Request $request, \Closure $next)
    {
        $path = '/'.trim(config('deep_admin.route.prefix'), '/');

        config(['session.path' => $path]);

        if ($domain = config('deep_admin.route.domain')) {
            config(['session.domain' => $domain]);
        }

        return $next($request);
    }
}
