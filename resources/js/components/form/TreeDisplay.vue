<template>
  <div class="tree-wrap">
    <el-tree ref="tree"
      :show-checkbox="showCheckbox"
      :data="data"
      :props="defaultProps"
      node-key="id"
      :default-expanded-keys="expandedList"
      :current-node-key="currentNodeKey"
      highlight-current
      @node-click="handleNodeClick"
      @node-expand="handleNodeExpand"
       >
    </el-tree>
  </div>
</template>
<script>
export default {
  components: {},
  props: {
    attrs: Object,
    data: Array,
    isMultiple: Boolean
  },
  data() {
    return {
      defaultProps: {
        children: "children",
        label: "label",
      },
      highlightCurrent: true, // 保持高亮
      showCheckbox: false, // 是否可选择 true表示多选， false表示单选
      // isMultiple: false, // 是否可选择 true表示多选， false表示单选
      currentNode: [],
      checkedList: [],
      expandedList: [], // 默认展开的数据
      currentNodeKey: '', // 当前被选中的状态
    };
  },
  watch: {
    checkedList() {
      if (this.checkedList.length > 0) {
        this.$bus.emit("getDataInfo", this.checkedList);
      }
    }
  },
  created() {
    // 默认选中
    this.$nextTick(()=>{
      this.$refs.tree.setCurrentKey(this.currentNodeKey); //进来就默认选中006
    }) 
  },
  mounted() {
    // 设置默认选中的数据
    this.$bus.on("setTreeCurrentKey", (query) => {
      this.$nextTick(()=>{
        this.$refs.tree.setCurrentKey(this.currentNodeKey); //进来就默认选中006
      }) 
    })
  },
  destroyed() {},
  methods: {
    handleNodeClick(data) {
      this.checkedList = [data.id]
      // 选中的字段
      this.currentNodeKey = data.id
    },
    // 节点被展开时触发的事件
    handleNodeExpand(node) {
      // 设置默认展开的
      this.expandedList.push(node.id)
    }
  },
};
</script>
<style lang="scss">
.tree-wrap {
  padding-left: 10px;
  padding-right: 10px;
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
.el-tree--highlight-current .el-tree-node.is-current>.el-tree-node__content {
  background-color: #e5e6e4 !important;
}
</style>

