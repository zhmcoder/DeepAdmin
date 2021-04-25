<?php

namespace Andruby\DeepAdmin\Services;


use App\Admin\Models\ColumnInfo;
use Illuminate\Support\Facades\Cache;

/**
 * @method static GridCacheService instance()
 *
 * Class ActivityConfigService
 * @package App\Api\Services
 */
class GridCacheService
{
    public static function __callStatic($method, $params): GridCacheService
    {
        return new self();
    }

    public function get_cache_value($model, $key, $where_field_value, $where_field, $value_field, $is_delete = 0)
    {
        $value = Cache::get($key);
        if (empty($value)) {
//            $model = $model::query();
            if ($is_delete) {
                $model = $model->withTrashed(); // 查询已删除数据
            }
            $value = $model->where($where_field, $where_field_value)->value($value_field);
            $value = $value ? $value : '';
            Cache::put($key, $value, config('deep_admin.GRID_RELATED_DATA_CACHE_EXPIRE_TIME'));
        }
        return $value;
    }

}
