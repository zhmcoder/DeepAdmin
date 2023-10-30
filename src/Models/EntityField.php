<?php

namespace Andruby\DeepAdmin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EntityField.
 *
 * @property Role[] $roles
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $comment
 * @property string $default_value
 * @property string $form_name
 * @property string $form_type
 * @property string $form_comment
 * @property string $form_params
 * @property int $is_show
 * @property int $is_show_inline
 * @property int $is_edit
 * @property int $is_required
 * @property int $entity_id
 * @property int $order
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property-read \Andruby\DeepAdmin\Models\Entity $entities
 * @property-read \Illuminate\Database\Eloquent\Collection|\Andruby\DeepAdmin\Models\Entity[] $permissions
 * @property-read int|null $permissions_count
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereFormComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereFormName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereFormParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereFormType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereIsEdit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereIsShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereIsShowInline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\EntityField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EntityField extends Model
{
    const SHOW_ENABLE = 1;
    const SHOW_DISABLE = 0;

    const SHOW_INLINE = 1;
    const SHOW_NOT_INLINE = 0;

    const EDIT_ENABLE = 1;
    const EDIT_DISABLE = 0;

    const REQUIRED_ENABLE = 1;
    const REQUIRED_DISABLE = 0;

    protected $fillable = ['id','name','entity_id'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'created_at' => "datetime:Y-m-d H:i:s",
        'updated_at' => "datetime:Y-m-d H:i:s",
    ];

    public static $listField = [
        'name' => '名称',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('deep_admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('deep_admin.database.entity_fields_table'));

        parent::__construct($attributes);
    }

    /**
     * 模型关联
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    /**
     * @inheritDoc
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}
