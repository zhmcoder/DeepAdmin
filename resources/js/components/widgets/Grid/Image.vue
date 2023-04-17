<template>
  <div v-if="srcList.length">
    <el-image
      v-for="(item, index) in srcList"
      :key="index"
      :style="attrs.style"
      :class="attrs.className"
      :fit="attrs.fit"
      :lazy="attrs.lazy"
      :src="item"
      :scroll-container="attrs.scrollContainer"
      :preview-src-list="value"
    />
  </div>
  <el-image
    v-else
    :style="attrs.style"
    :class="attrs.className"
    :fit="attrs.fit"
    :lazy="attrs.lazy"
    :src="src"
    :scroll-container="attrs.scrollContainer"
    :preview-src-list="previewSrcList"
  />
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
    if (this.attrs.max) {
      var newValue = JSON.parse(JSON.stringify(this.value));
      this.srcList = newValue.splice(0, this.attrs.max);
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
