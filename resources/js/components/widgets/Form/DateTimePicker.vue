<template>
  <el-date-picker
    v-model="vm"
    :style="attrs.style"
    :class="attrs.className"
    :readonly="attrs.readonly"
    :disabled="attrs.disabled"
    :editable="attrs.editable"
    :clearable="attrs.clearable"
    :size="attrs.size"
    :placeholder="attrs.placeholder"
    :start-placeholder="attrs.startPlaceholder"
    :end-placeholder="attrs.endPlaceholder"
    :type="attrs.type"
    :format="attrs.format"
    :align="attrs.align"
    :popper-class="attrs.popperClass"
    :range-separator="attrs.rangeSeparator"
    :value-format="attrs.valueFormat"
    :prefix-icon="attrs.prefixIcon"
    :clear-icon="attrs.clearIcon"
    :default-time="attrs.defaultTime"
    @change="onChange"
  ></el-date-picker>
</template>
<script>
import { FormItemComponent } from "@/mixins.js";
export default {
  mixins: [FormItemComponent],
  data() {
    return {
      vm: this._.cloneDeep(this.value),
      init: false,
    };
  },
  mounted() {},
  watch: {
    value(value) {
      if (!this.init) {
        this.vm = value;
        this.init = true;
      }
    },
  },
};
</script>