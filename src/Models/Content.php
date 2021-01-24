<?php

namespace Andruby\DeepAdmin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Entities.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content external()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content whereEnableComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content whereIsInternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content whereIsShowContentManage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content whereTableName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\Content whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Content extends Model
{
    protected $guarded = [];
    public $table = '';

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'created_at' => "Y-m-d H:i:s",
        'updated_at' => "Y-m-d H:i:s",
    ];

    public static $listField = [
        'name' => '名称',
        'table_name' => '数据库表名',
        'description' => '描述',
    ];

    public function __construct($table = null, array $attributes = [])
    {
        if (empty($table)) {
            $entityId = request('entity_id');
            if ($entityId) {
                $entity = Entity::find($entityId);
                $table = $entity->table_name;
            }
        }

        // todo 多选需要标记字段类型
        $entityFieldList = EntityField::query()
            ->where('entity_id', request('entity_id'))
            ->whereIn('form_type', ['checkbox', 'checkboxTable', 'selectMulti', 'selectMultiTable', 'inputDecimal', 'cascade', 'cascadeMulti', 'uploadMulti'])
            ->get()->toArray();

        $array = ['checkbox', 'checkboxTable', 'selectMulti', 'selectMultiTable', 'cascade', 'cascadeMulti', 'uploadMulti'];
        foreach ($entityFieldList as $key => $val) {
            // 转换成数组
            if (in_array($val['form_type'], $array)) {
                $this->casts[$val['name']] = 'array';
            }
            // 转换成float
            if ($val['form_type'] == 'inputDecimal') {
                $this->casts[$val['name']] = 'float';
            }
        }

        $connection = config('deep_admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable($table);

        parent::__construct($attributes);
    }

    /**
     * @inheritDoc
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        // TODO: Implement resolveChildRouteBinding() method.
    }
}
