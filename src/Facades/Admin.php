<?php

namespace Andruby\DeepAdmin\Facades;

use Illuminate\Support\Facades\Facade;

class Admin extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Andruby\DeepAdmin\Admin::class;
    }
}
