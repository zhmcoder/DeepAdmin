<template>
  <div class="tree-wrap">
    <el-tree ref="tree"
      :check-strictly="!isMultiple"
      show-checkbox
      :data="data"
      :props="defaultProps"
      node-key="id"
      @node-click="handleNodeClick"
      :class="!isMultiple ? 'icon-select' : ''"
      @check="handleCurrentChecked"
      @check-change="checkChange">
    </el-tree>
  </div>
</template>
<script>
export default {
  components: {},
  props: {
    attrs: Object,
    data: Array
  },
  data() {
    return {
      // data: [
      //   {
      //     label: "一级 1",
      //     id: 1,
      //     children: [
      //       {
      //         label: "二级 1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1",
      //         id: 11,
      //         children: [
      //           {
      //             label: "三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1三级 1-1-1",
      //             id: 111
      //           },
      //         ],
      //       },
      //     ],
      //   },
      //   {
      //     label: "一级 2",
      //     id: 2,
      //     children: [
      //       {
      //         label: "二级 2-1",
      //         id: 21,
      //         children: [
      //           {
      //             label: "三级 2-1-1",
      //             id: 22,
      //           },
      //           {
      //             label: "三级 2-1-4",
      //             id: 25,
      //           },
      //         ],
      //       },
      //       {
      //         label: "二级 2-2",
      //         id: 23,
      //         children: [
      //           {
      //             id: 24,
      //             label: "三级 2-2-1",
      //           },
      //           {
      //             label: "三级 2-1-3",
      //             id: 26,
      //           },
      //         ],
      //       },
      //     ],
      //   }
      // ],
      defaultProps: {
        children: "children",
        label: "label",
      },
      highlightBd: false, // 保持高亮
      // showCheckbox: false, // 是否可选择 true表示多选， false表示单选
      isMultiple: false, // 是否可选择 true表示多选， false表示单选
      currentNode: []
    };
  },
  created() {},
  mounted() {},
  destroyed() {},
  methods: {
    handleNodeClick(item,node,self){ //自己定义的editCheckId，防止单选出现混乱
      if (!this.isMultiple) {
        this.editCheckId=item.id;
        this.$refs.tree.setCheckedKeys([item.id])
      }
    },
    checkChange(item,node,self){
      if (!this.isMultiple) {
        if (node == true) {
            this.editCheckId=item.id;
            this.$refs.tree.setCheckedKeys([item.id])
            this.$emit("change", [item.id]);
        }else {
            if (this.editCheckId == item.id) {
              this.$refs.tree.setCheckedKeys([item.id])
              this.$emit("change", [item.id]);
            }
        }
      }
    },
    handleCurrentChecked(nodeObj, selectedObj) {
      console.log(selectedObj.checkedKeys);
      console.log(selectedObj.checkedNodes); //这是选中的节点数组
      // 去调用后台的链接
      var newNode = selectedObj.checkedNodes.filter(item => (!item.children || (item.children && item.children.length==0)))
      var list = [];
      newNode.map(item => {
        list.push(item.id)
      })
      console.log('list', list)
      this.$emit("change", list);
    },
  },
};
</script>
<style lang="scss">
.tree-wrap {
  padding-right: 10px;
}
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
  border: 1px solid #adadad;
  background: #f8f8f8;
}

/* //有子节点 且已展开 */
.icon-select .el-tree-node__expand-icon.expanded.el-icon-caret-right:before {
  content: "\e6d8";
  color: #686868;
  display: block;
  font-size: 14px;
  background-size: 14px;
  border: 1px solid #adadad;
  background: #f8f8f8;
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

.el-tree .el-tree-node__content:hover {
  background-color: transparent;
}
.icon-select .el-tree-node .is-leaf + .el-checkbox .el-checkbox__inner{
  display: inline-block;
}
// 最后一个节点可选择
.icon-select .el-tree-node .el-checkbox .el-checkbox__inner{
  display: none;
}
.icon-select .el-tree-node__content>.el-tree-node__expand-icon {
  padding-top: 6px !important;
}
.el-tree .el-tree-node__content {
  height: auto;
  // display: flex;
  // align-items: flex-start;
}
.el-tree .el-tree-node__label{
  word-break: normal;
  width: auto;
  display: block;
  white-space: pre-wrap;
  word-wrap: break-word;
  overflow: hidden;
  height: auto;
  padding: 6px 0;
}
</style>
