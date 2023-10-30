<?php

namespace Andruby\DeepAdmin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class Entities.
 *
 * @property Role[] $roles
 * @property int $id
 * @property string $name
 * @property string $table_name
 * @property string $description
 * @property int $is_internal
 * @property int $enable_comment
 * @property int $is_show_content_manage
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Andruby\DeepAdmin\Models\EntityField[] $fields
 * @property-read int|null $fields_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity external()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity whereEnableComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity whereIsInternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity whereIsShowContentManage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity whereTableName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Entity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Files extends Model
{
    protected $guarded = [];

    protected $table = 'admin_files';

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'created_at' => "datetime:Y-m-d H:i:s",
        'updated_at' => "datetime:Y-m-d H:i:s",
    ];

    /**
     * @inheritDoc
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}
