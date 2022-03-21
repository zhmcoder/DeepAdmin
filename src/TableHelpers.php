<?php

namespace Andruby\DeepAdmin;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class TableHelpers
{
    /**
     * 创建表
     *
     * @param $table_name
     * @return mixed
     * @throws \Exception
     */
    public static function create_table($table_name)
    {
        try {
            if (Schema::hasTable($table_name)) {
                return \Admin::responseError("数据库表已存在");
            }
            Schema::create($table_name, function (Blueprint $table) {
                $table->increments('id');
                $table->softDeletes();
                $table->timestamps();

                $table->integer('add_user_id')->default(0)->nullable(); // 添加用户
                $table->integer('edit_user_id')->default(0)->nullable(); // 编辑用户
                $table->integer('del_user_id')->default(0)->nullable(); // 删除用户

                $table->engine = 'InnoDB';
            });
        } catch (\Exception $e) {
            throw new \Exception("创建数据库表异常");
        }
    }

    /**
     * 重命名表名
     * @param $table_from
     * @param $table_to
     * @throws \Exception
     */
    public static function rename_table($table_from, $table_to)
    {
        try {
            if (Schema::hasTable($table_to)) {
                throw new \Exception("数据库表已存在");
            }
            Schema::rename($table_from, $table_to);

        } catch (\Exception $e) {
            throw new \Exception("更新数据库表异常");
        }
    }

    /**
     * 检测表是否存在
     *
     * @param $table_name
     * @return mixed
     * @throws \Exception
     */
    public static function check_exist_table($table_name)
    {
        try {
            return Schema::hasTable($table_name);
        } catch (\Exception $e) {
            throw new \Exception("检测数据库表异常");
        }
    }

    /**
     * 删除表
     *
     * @param $table_name
     * @throws \Exception
     */
    public static function drop_table($table_name)
    {
        try {
            Schema::dropIfExists($table_name);
        } catch (\Exception $e) {
            throw new \Exception("删除数据库表异常");
        }
    }

    /**
     * 添加表字段
     *
     * @param $tableName
     * @param $fieldInfo
     * @return mixed
     * @throws \Exception
     */
    public static function create_field($tableName, $fieldInfo)
    {
        try {
            if (!Schema::hasTable($tableName)) {
                return \Admin::responseError("数据库表不存在");
            }
            if (Schema::hasColumn($tableName, $fieldInfo['name'])) {
                return \Admin::responseError("数据库表字段已存在");
            }
            if (!isset(config('deep_admin.db_table_field_type')[$fieldInfo['type']])) {
                return \Admin::responseError("无效字段类型");
            }

            Schema::table($tableName, function (Blueprint $table) use ($fieldInfo) {
                $type = $fieldInfo['type'];

                $length = isset($fieldInfo['field_length']) ? intval($fieldInfo['field_length']) : 0;
                $total = isset($fieldInfo['field_total']) ? intval($fieldInfo['field_total']) : 0;
                $scale = isset($fieldInfo['field_scale']) ? intval($fieldInfo['field_scale']) : 0;

                if (in_array($type, ['char', 'string'])) {
                    $table = $table->$type($fieldInfo['name'], $length > 0 ? $length : 255)
                        ->comment($fieldInfo['comment'])
                        ->default(strval($fieldInfo['default_value']));
                } elseif (Str::contains(Str::lower($type), 'integer')) {
                    $table = $table->$type($fieldInfo['name'])
                        ->comment($fieldInfo['comment'])
                        ->default(intval($fieldInfo['default_value']));
                } elseif (in_array($type, ['float', 'double', 'decimal', 'unsignedDecimal'])) {
                    if ($total > 0 && $scale > 0 && $total > $scale) {
                        $table = $table->$type($fieldInfo['name'], $total, $scale)
                            ->comment($fieldInfo['comment'])
                            ->default(doubleval($fieldInfo['default_value']));
                    } else {
                        $table = $table->$type($fieldInfo['name'])
                            ->comment($fieldInfo['comment'])
                            ->default(doubleval($fieldInfo['default_value']));
                    }
                } else {
                    $table = $table->$type($fieldInfo['name'])->comment($fieldInfo['comment']);
                }

                if (!$fieldInfo['is_required']) {
                    $table = $table->nullable();
                }

                if ($fieldInfo['is_unique']) {
                    $table = $table->unique();
                }

            });
        } catch (\Exception $e) {
            throw new \Exception("创建数据库表字段异常");
        }
    }

    /**
     * 删除表字段
     *
     * @param $tableName
     * @param $fieldName
     * @throws \Exception
     */
    public static function drop_field($tableName, $fieldName)
    {
        try {
            Schema::table($tableName, function (Blueprint $table) use ($fieldName) {
                $table->dropColumn($fieldName);
            });
        } catch (\Exception $e) {
            throw new \Exception("删除数据库表字段异常");
        }
    }

    /**
     * 修改表字段
     *
     * @param $tableName
     * @param $fieldInfo
     * @return mixed
     * @throws \Exception
     */
    public static function update_field($tableName, $fieldInfo)
    {
        try {
            if (!Schema::hasTable($tableName)) {
                return \Admin::responseError("数据库表不存在");
            }
            if (!Schema::hasColumn($tableName, $fieldInfo['name'])) {
                return \Admin::responseError("数据库表字段不存在");
            }
            if (!isset(config('deep_admin.db_table_field_type')[$fieldInfo['type']])) {
                return \Admin::responseError("无效字段类型");
            }

            Schema::table($tableName, function (Blueprint $table) use ($fieldInfo) {
                $type = $fieldInfo['type'];

                $length = isset($fieldInfo['field_length']) ? intval($fieldInfo['field_length']) : 255;
                $total = isset($fieldInfo['field_total']) ? intval($fieldInfo['field_total']) : 0;
                $scale = isset($fieldInfo['field_scale']) ? intval($fieldInfo['field_scale']) : 0;

                if (in_array($type, ['char', 'string'])) {
                    $table = $table->$type($fieldInfo['name'], $length)
                        ->comment($fieldInfo['comment'])
                        ->default(strval($fieldInfo['default_value']));
                } elseif (Str::contains(Str::lower($type), 'integer')) {
                    $table = $table->$type($fieldInfo['name'])
                        ->comment($fieldInfo['comment'])
                        ->default(intval($fieldInfo['default_value']));
                } elseif (in_array($type, ['float', 'double', 'decimal', 'unsignedDecimal'])) {
                    if ($total > 0 && $scale > 0 && $total > $scale) {
                        $table = $table->$type($fieldInfo['name'], $total, $scale)
                            ->comment($fieldInfo['comment'])
                            ->default(doubleval($fieldInfo['default_value']));
                    } else {
                        $table = $table->$type($fieldInfo['name'])
                            ->comment($fieldInfo['comment'])
                            ->default(doubleval($fieldInfo['default_value']));
                    }
                } elseif (in_array($type, ['date', 'dateTime'])) {
                    $table = $table->$type($fieldInfo['name'])
                        ->comment($fieldInfo['comment']);
                } else {
                    $table = $table->$type($fieldInfo['name'])
                        ->comment($fieldInfo['comment'])
                        ->default(strval($fieldInfo['default_value']));
                }

                if (!$fieldInfo['is_required']) {
                    $table = $table->nullable();
                }
                $table = $table->change();

            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 修改表字段
     *
     * @param $tableName
     * @param $oldFieldName
     * @param $newFieldName
     * @return mixed
     * @throws \Exception
     */
    public static function rename_field($tableName, $oldFieldName, $newFieldName)
    {
        try {
            if (!Schema::hasTable($tableName)) {
                return \Admin::responseError("数据库表不存在");
            }
            if (!Schema::hasColumn($tableName, $oldFieldName)) {
                return \Admin::responseError("旧数据库表字段不存在");
            }
            if (Schema::hasColumn($tableName, $newFieldName)) {
                return \Admin::responseError("新数据库表字段已存在");
            }

            Schema::table($tableName, function (Blueprint $table) use ($oldFieldName, $newFieldName) {
                $table->renameColumn($oldFieldName, $newFieldName);
            });

        } catch (\Exception $e) {
            throw new \Exception("更新数据库表字段异常");
        }
    }

    /**
     * 检测表字段是否存在
     *
     * @param $tableName
     * @param $field
     * @return true
     */
    public static function check_exist_table_field($tableName, $field)
    {
        if (Schema::hasColumn($tableName, $field)) {
            return true;
        }
        return false;
    }
}
