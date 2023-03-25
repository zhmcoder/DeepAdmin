<template>
  <div>
    <el-image
      v-for="(item, index) in "
      :key="index"
      :style="attrs.style"
      :class="attrs.className"
      :fit="attrs.fit"
      :lazy="attrs.lazy"
      :src="src"
      :scroll-container="attrs.scrollContainer"
      :preview-src-list="previewSrcList"
    />
  </div>
</template>
<script>
import { getFileUrl } from "@/utils";
export default {
  props: {
    attrs: Object,
    value: {
      default: null,
    },
    row: Object,
    columnValue: {
      default: null,
    },
  },
  data() {
    return {
      srcList: [],
    };
  },
  mounted() {
    console.log("attrs", this.attrs);
    if (this.attrs.max) {
      this.srcList = this.value.splice(0, this.attrs.max);
    } else {
    }
  },
  computed: {
    src() {
      return getFileUrl(this.attrs.host, this.value);
    },
    previewSrcList() {
      if (!this.attrs.preview) return [];
      if (this._.isArray(this.columnValue)) {
        return this.columnValue.map((item) => {
          return getFileUrl(this.attrs.host, item);
        });
      } else {
        return [getFileUrl(this.attrs.host, this.columnValue)];
      }
    },
  },
};
</script>
