<template>
  <el-button
    slot="reference"
    :type="action.type"
    :size="action.size"
    :plain="action.plain"
    :round="action.round"
    :circle="action.circle"
    :disabled="action.disabled"
    :icon="action.icon"
    :autofocus="action.autofocus"
    :loading="loading"
    class="action-button"
    @click="showConfirm"
    >{{ action.content }}</el-button
  >
</template>
<script>
/**
 * attrs 删除按钮--提示用户确认动作。
 * @param { action.type } 按钮的类型--primary / success / warning / danger / info / text
 * @param { action.size } 按钮的尺寸--medium / small / mini
 * @param { action.plain } 是否是朴素按钮
 * @param { action.round } 是否圆角按钮
 * @param { action.circle } 是否圆形按钮
 * @param { action.disabled } 是否禁用状态
 * @param { action.icon } 图标类名
 * @param { action.autofocus } 是否默认聚焦
 * @param { action.loading } 是否显示按钮ok
 * @param { action.content } 按钮文本
 * @param { action.message } 提示文案confirm
 * @param { action.title } 题头confirm
 */
export default {
  name: "DeleteButton",
  props: {
    scope: Object,
    action: Object,
    key_name: String,
  },
  data() {
    return {
      loading: false,
    };
  },
  methods: {
    showConfirm() {
      this.$confirm(this.action.message, this.action.title || '提示', {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
      })
        .then(() => {
            this.onHandle();
        })
        .catch(() => {
        });
    },
    onHandle() {
      this.loading = true;
      this.$http
        //deep admin start
        .delete(
          this.action.resource + "/" + this.key + "?" + this.action.params
        )
        //depp admin end
        .then(({ code }) => {
          if (code === 200) this.$bus.emit("tableReload");
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
  computed: {
    colum() {
      return this.scope.colum;
    },
    row() {
      return this.scope.row;
    },
    key() {
      return this.scope.row[this.key_name];
    },
  },
};
</script>
<style lang="scss" scoped></style>
