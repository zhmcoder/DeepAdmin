<template>
  <div>
    <el-tree
      :data="data"
      :props="defaultProps"
      :highlight-current="highlightBd"
      :show-checkbox="showCheckbox"
      @node-click="handleNodeClick"
      :class="!showCheckbox ? 'icon-select' : ''"
    ></el-tree>
  </div>
</template>
<script>
export default {
  components: {},
  props: {
    attrs: Object,
  },
  data() {
    return {
      data: [
        {
          label: "一级 1",
          id: 2,
          children: [
            {
              label: "二级 1-1",
              id: 12,
              children: [
                {
                  label: "三级 1-1-1",
                  id: 1
                },
              ],
            },
          ],
        },
        {
          label: "一级 2",
          children: [
            {
              label: "二级 2-1",
              children: [
                {
                  label: "三级 2-1-1",
                },
              ],
            },
            {
              label: "二级 2-2",
              children: [
                {
                  label: "三级 2-2-1",
                },
              ],
            },
          ],
        },
        {
          label: "一级 3",
          children: [
            {
              label: "二级 3-1",
              children: [
                {
                  label: "三级 3-1-1",
                },
              ],
            },
            {
              label: "二级 3-2",
              children: [
                {
                  label: "三级 3-2-1",
                },
              ],
            },
          ],
        },
      ],
      defaultProps: {
        children: "children",
        label: "label",
      },
      highlightBd: true, // 保持高亮
      showCheckbox: true, // 是否可选择
    };
  },
  created() {},
  mounted() {},
  destroyed() {},
  methods: {
    handleNodeClick(data) {
      console.log(data);
      this.$emit("change", []);
    },
  },
};
</script>
<style lang="scss">
.el-tree .el-tree-node__expand-icon.expanded {
  -webkit-transform: rotate(0deg);
  transform: rotate(0deg);
}

.el-tree .el-tree-node__expand-icon.expanded {
  -webkit-transform: rotate(0deg);
  transform: rotate(0deg);
}

/* //有子节点 且未展开 */
.icon-select .el-icon-caret-right:before {
  content: "\e6d9";
  color: #686868;
  display: block;
  font-size: 14px;
  background-size: 14px;
  border: 1px solid #ADADAD;
  background: #F8F8F8;
}

/* //有子节点 且已展开 */
.icon-select .el-tree-node__expand-icon.expanded.el-icon-caret-right:before {
  content: "\e6d8";
  color: #686868;
  display: block;
  font-size: 14px;
  background-size: 14px;
  border: 1px solid #ADADAD;
  background: #F8F8F8;
}

/* //没有子节点 */
.icon-select .el-tree-node__expand-icon.is-leaf::before {
  content: "";
  display: block;
  width: 0px !important;
  height: 0px !important;
  border: 0px !important;
  color: transparent !important;
}

/* //高亮字体颜色 */
.el-tree--highlight-current .el-tree-node.is-current > .el-tree-node__content {
  color: #409eff !important;
}
</style>
