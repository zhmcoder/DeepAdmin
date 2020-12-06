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
class Entity extends Model
{

    const COMMENT_ENABLE = 1;
    const COMMENT_DISABLE = 0;

    const INTERNAL_YES = 1;
    const INTERNAL_NO = 0;

    const CONTENT_MANAGE_YES = 1;
    const CONTENT_MANAGE_NO = 0;

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'created_at' => "Y-m-d H:i:s",
        'updated_at' => "Y-m-d H:i:s",
        'actions' => 'array'
    ];

    public static $listField = [
        'name' => '名称',
        'table_name' => '数据库表名',
        'description' => '描述',
    ];

    public function __construct(array $attributes = [])
    {
        $connection = config('deep_admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('deep_admin.database.entities_table'));

        parent::__construct($attributes);
    }

    public function fields()
    {
        return $this->hasMany('Andruby\DeepAdmin\Models\EntityField', 'entity_id');
    }

    /**
     * 限制查询外部模型
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExternal($query)
    {
        return $query->where('is_internal', self::INTERNAL_NO);
    }

    /**
     * @inheritDoc
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}
