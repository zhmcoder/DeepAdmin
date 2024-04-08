<?php

namespace Andruby\DeepAdmin\Services;

use Andruby\DeepAdmin\Models\EntityField;
use Illuminate\Support\Facades\Cache;

/**
 * @method static EntityCacheService instance()
 *
 * Class EntityCacheService
 * @package App\Api\Services
 */
class EntityCacheService
{
    public static function __callStatic($method, $params): EntityCacheService
    {
        return new self();
    }

    public function field($fieldId)
    {
        $key = 'ENTITY:FIELD:' . $fieldId;
        $value = Cache::get($key);

        if (empty($value)) {
            $value = EntityField::query()->find($fieldId)->toArray();
            Cache::put($key, $value, config('deep_admin.ENTITY_FIELD_CACHE_EXPIRE_TIME'));
        }

        return $value;
    }

    public function list($entityId)
    {
        $key = 'ENTITY:FIELD:LIST:' . $entityId;
        $value = Cache::get($key);

        if (empty($value)) {
            $value = EntityField::query()
                ->where('entity_id', $entityId)
                ->whereIn('form_type', ['checkbox', 'checkboxTable', 'selectMulti', 'selectMultiTable', 'inputDecimal', 'cascade', 'cascadeMulti', 'uploadMulti'])
                ->get()->toArray();

            Cache::put($key, $value, config('deep_admin.ENTITY_FIELD_LIST_CACHE_EXPIRE_TIME', 5 * 60));
        }

        return $value;
    }

}
