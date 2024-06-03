<?php

namespace Andruby\DeepAdmin\Services;

use Andruby\DeepAdmin\Models\Entity;
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

    public function entity_table($table)
    {
        if (empty($table)) {
            return [];
        }

        $key = 'ENTITY:' . $table;
        $value = Cache::get($key);

        if (empty($value)) {
            $entity = Entity::query()->where('table_name', $table)->first();
            if ($entity) {
                $value = $entity->toArray();
                Cache::put($key, $value, config('deep_admin.ENTITY_TABLE_CACHE_EXPIRE_TIME', 5 * 60));
            }
        }

        return $value;
    }

    public function entity($entityId)
    {
        if (empty($entityId)) {
            return [];
        }

        $key = 'ENTITY:' . $entityId;
        $value = Cache::get($key);

        if (empty($value)) {
            $entity = Entity::query()->find($entityId);
            if ($entity) {
                $value = $entity->toArray();
                Cache::put($key, $value, config('deep_admin.ENTITY_CACHE_EXPIRE_TIME', 5 * 60));
            }
        }

        return $value;
    }

    public function field($fieldId)
    {
        if (empty($fieldId)) {
            return [];
        }

        $key = 'ENTITY:FIELD:' . $fieldId;
        $value = Cache::get($key);

        if (empty($value)) {
            $entityField = EntityField::query()->find($fieldId);
            if ($entityField) {
                $value = $entityField->toArray();
                Cache::put($key, $value, config('deep_admin.ENTITY_FIELD_CACHE_EXPIRE_TIME'));
            }
        }

        return $value;
    }

    public function list($entityId)
    {
        if (empty($entityId)) {
            return [];
        }

        $key = 'ENTITY:FIELD:LIST:' . $entityId;
        $value = Cache::get($key);

        if (empty($value)) {
            $value = EntityField::query()
                ->where('entity_id', $entityId)
                ->get()->toArray();

            Cache::put($key, $value, config('deep_admin.ENTITY_FIELD_LIST_CACHE_EXPIRE_TIME', 5 * 60));
        }

        return $value;
    }

}
