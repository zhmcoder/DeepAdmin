<?php


namespace Andruby\DeepAdmin\Controllers;


interface AdminResource
{
    public function grid();

    public function form($isEdit = false);

}
