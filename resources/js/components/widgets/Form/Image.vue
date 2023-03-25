
<template>
  <div>
    <el-image
      v-for="(item, index) in attrs.srcList"
      :key="index"
      style="width: 100px; height: 100px"
      :src="item"
      :preview-src-list="attrs.srcList"
      class="img"
    >
    </el-image>
  </div>
</template>
<script>
import { FormItemComponent } from "@/mixins.js";
export default {
  mixins: [FormItemComponent],
  props: ["attrs", "value"],
  methods: {},
};
</script>
<style scoped>
.img {
  margin-bottom: 4px;
  margin-right: 4px;
}
</style>