<?php

namespace Andruby\DeepAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsAttrValue extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public $timestamps = false;

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function allValues($goods_attr_id)
    {
        return self::query()->where('goods_attr_id', $goods_attr_id)->orderBy("sort")->get();
    }
}
