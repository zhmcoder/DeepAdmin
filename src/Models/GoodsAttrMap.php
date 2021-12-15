<?php

namespace Andruby\DeepAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsAttrMap extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * 产品销售属性关联本体
     * @return BelongsTo
     */
    public function attr(): BelongsTo
    {
        return $this->belongsTo(GoodsAttr::class, 'attr_id');
    }

    /**
     * 产品销售属性值列表
     * @return HasMany
     */
    public function value_map(): HasMany
    {
        return $this->hasMany(GoodsAttrValueMap::class, 'attr_map_id', 'id')
            ->orderBy('index');
    }
}
