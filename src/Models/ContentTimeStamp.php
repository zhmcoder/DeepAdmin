<?php

namespace Andruby\DeepAdmin\Models;

/**
 * Class Entities.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp external()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp whereEnableComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp whereIsInternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp whereIsShowContentManage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp whereTableName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Andruby\DeepAdmin\Models\ContentTimeStamp whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContentTimeStamp extends Content
{
    /**
     * 默认使用时间戳戳功能
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 获取当前时间
     *
     * @return int
     */
    public function freshTimestamp()
    {
        return time();
    }

    /**
     * 避免转换时间戳为时间字符串
     *
     * @param DateTime|int $value
     * @return DateTime|int
     */
    public function fromDateTime($value)
    {
        return $value;
    }

    /**
     * 从数据库获取的为获取时间戳格式
     *
     * @return string
     */
    public function getDateFormat()
    {
        return 'U';
    }

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}
