<template slot-scope="props">
  <el-form class="demo-table-expand">
    <el-form-item v-for="(item, i) in info_list" :key="i" :label="item.label">
      <span class="text_value" v-if="item.href"
        ><a :href="item.href" target="_blank">{{ item.name }}</a></span
      >
      <el-image
        v-else-if="item.img"
        title="预览图片"
        :src="item.img"
        fit="cover"
        class="show-image"
      />
      <div v-else>
        <span class="text_value" v-html="item.name"></span>
        <i
          v-if="item.icon"
          :class="item.icon"
          :style="{ color: item.color, fontSize: '16px' }"
        ></i>
      </div>
    </el-form-item>
  </el-form>
</template>
<style>
.demo-table-expand {
  font-size: 0;
}

.demo-table-expand label {
  width: 100px;
  color: #99a9bf;
  height: 40px;
}

.demo-table-expand .el-form-item {
  margin-right: 0;
  margin-bottom: 0;
  width: 100%;
  -webkit-font-smoothing: antialiased;
  font-size: 14px;
}

.text_value {
  color: #606266;
  box-sizing: border-box;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: normal;
  word-break: break-all;
  line-height: 23px;
  padding-left: 10px;
  padding-right: 10px;
  -webkit-font-smoothing: antialiased;
  font-size: 14px;
}
</style>

<script>
export default {
  props: ["attrs", "value"],
  data() {
    return {
      info_list: [],
    };
  },
  created() {
    this.info_list = this.attrs.info_list;
  },
};
</script>
<style>
.show-image {
  max-width: 400px;
}
</style>
