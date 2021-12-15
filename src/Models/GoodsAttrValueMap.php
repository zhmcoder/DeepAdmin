<?php

namespace Andruby\DeepAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsAttrValueMap extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public $timestamps = false;

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * 产品销售属性值关联的本体（详细信息）
     * @return BelongsTo
     */
    public function attr_value(): BelongsTo
    {
        return $this->belongsTo(GoodsAttrValue::class, 'attr_value_id');
    }
}
