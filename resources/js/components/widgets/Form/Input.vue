<template>
  <el-input
    :style="attrs.style"
    :class="attrs.className"
    :type="attrs.type"
    :maxlength="attrs.maxlength"
    :minlength="attrs.minlength"
    :show-word-limit="attrs.showWordLimit"
    :placeholder="attrs.placeholder"
    :clearable="attrs.clearable"
    :show-password="attrs.showPassword"
    :disabled="attrs.disabled"
    :size="attrs.size"
    :prefix-icon="attrs.prefixIcon"
    :suffix-icon="attrs.suffixIcon"
    :rows="attrs.rows"
    :autosize="attrs.autosize"
    :autocomplete="attrs.autocomplete"
    :readonly="attrs.readonly"
    :max="attrs.max"
    :min="attrs.min"
    :step="attrs.step"
    :resize="attrs.resize"
    :autofocus="attrs.autofocus"
    :form="attrs.form"
    :label="attrs.label"
    :tabindex="attrs.tabindex"
    :validate-event="attrs.validateEvent"
    :value="value"
    @input="onChange"
  >
    <template slot="prepend" v-if="attrs.prepend">{{ attrs.prepend }}</template>
    <template slot="append" v-if="attrs.append">{{ attrs.append }}</template>
  </el-input>
</template>
<script>
import { FormItemComponent } from "@/mixins.js";
export default {
  mixins: [FormItemComponent],
  props: ["attrs", "value", "form_data"],
  methods: {
    onChange(value) {
      console.log('90909');
      if (this.attrs.type == "number") {
        value = Number(value);
      }
      this.$emit("change", value);
    }
  }
};
</script>
<style scoped>
/deep/ .el-input__suffix{
  position: absolute;
  top: 0;
  -webkit-transition: all .3s;
  height: 100%;
  color: #C0C4CC;
  text-align: center;
  z-index: 2000;
  background-color: #FFF;
  height: 30px;
  box-sizing: border-box;
  margin-top: 1px;
}

</style>