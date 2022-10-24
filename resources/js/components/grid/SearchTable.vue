<template>
  <div class="grid-container">
    <div ref="topView">
      <el-card
        shadow="never"
        :body-style="{ padding: 0 }"
        class="margin-bottom-sm"
      >
        <div class="filter-form">
          <el-form :inline="true" :model="filterFormData" v-if="filterFormData">
            <template v-for="(item, index) in attrs.filter.filters">
              <!-- 单独展示一行 -->
              <el-form-item
                :key="index"
                :label="item.label"
                class="form-bottom"
              >
                <ItemDiaplsy
                  v-model="filterFormData[item.column]"
                  :form-item="item"
                  :form-items="attrs.filters"
                  :form-data="filterFormData"
                />
              </el-form-item>
            </template>
            <el-form-item>
              <el-button type="primary" @click="onFilterSubmit">搜索</el-button>
              <el-button @click="onFilterReset">重置</el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-card>
      <el-table
        ref="multipleTable"
        :data="tableData"
        v-loading="loading"
        :row-key="attrs.attributes.rowKey"
        :max-height="attrs.attributes.maxHeight"
        :stripe="attrs.attributes.stripe"
        :border="attrs.attributes.border"
        :size="attrs.attributes.size"
        :fit="attrs.attributes.fit"
        :show-header="attrs.attributes.showHeader"
        :highlight-current-row="attrs.attributes.highlightCurrentRow"
        :empty-text="attrs.attributes.emptyText"
        :tooltip-effect="attrs.attributes.tooltipEffect"
        :default-expand-all="attrs.attributes.defaultExpandAll"
        @selection-change="onTableselectionChange"
      >
        <el-table-column v-if="!attrs.isMultiple" align="center" width="55" label="选择">
          <template slot-scope="scope">
            <el-radio @change.native="handleSelectionChange(scope.row)" v-model="seleted" :label="scope.row.id">&nbsp;</el-radio>
          </template>
        </el-table-column>
        <el-table-column v-if="attrs.isMultiple" :reserve-selection="true" align="center" type="selection" width="55">
        </el-table-column>
        <el-table-column
          v-if="attrs.attributes.selection"
          align="center"
          type="selection"
        ></el-table-column>
        <el-table-column
          v-if="attrs.tree"
          align="center"
          width="80"
        ></el-table-column>
        <template v-for="column in attrs.columnAttributes">
          <el-table-column
            :type="column.type"
            :key="column.prop"
            :column-key="column.columnKey"
            :prop="column.prop"
            :label="column.label"
            :width="column.width"
            :sortable="column.sortable"
            :help="column.help"
            :align="column.align"
            :fixed="column.fixed"
            :header-align="column.headerAlign"
          >
            <template slot="header" slot-scope="scope">
              <span>{{ scope.column.label }}</span>
              <el-tooltip
                placement="top"
                v-if="column.help"
                :content="column.help"
              >
                <i class="el-icon-question hover"></i>
              </el-tooltip>
            </template>
            <template slot-scope="scope">
              <ColumnDisplay
                :scope="scope"
                :columns="attrs.columnAttributes"
                @downMove="downMove"
                @upMove="upMove"
              />
            </template>
          </el-table-column>
        </template>
        <el-table-column
          v-if="!attrs.attributes.hideActions"
          :label="attrs.attributes.actionLabel"
          prop="grid_actions"
          :fixed="attrs.attributes.actionFixed"
          :min-width="attrs.attributes.actionWidth"
          :align="attrs.attributes.actionAlign"
        >
          <template slot="header"></template>
          <template slot-scope="scope">
            <Actions
              v-if="scope.row.grid_actions && !scope.row.grid_actions.hide"
              :action_list="scope.row.grid_actions.data"
              :scope="scope"
              :key_name="attrs.keyName"
            />
          </template>
        </el-table-column>
      </el-table>
      <div class="table-page padding-xs" v-if="!attrs.hidePage">
        <el-pagination
          :layout="attrs.pageLayout"
          :hide-on-single-page="false"
          :total="pageData.total"
          :current-page="pageData.currentPage"
          :page-size="pageData.pageSize"
          :page-sizes="attrs.pageSizes"
          :background="attrs.pageBackground"
          @size-change="onPageSizeChange"
          @current-change="onPageCurrentChange"
        />
      </div>

      <!-- 单选或者多选数据显示 -->
      <el-card
        shadow="never"
        :body-style="{ padding: 0 }"
        class="margin-bottom-sm"
        v-if="selectList.length > 0 && attrs.showActions"
      >
        <div class="select-wrap">
          <div class="select-info" v-for="item in selectList" :key="item.id">
            <span>{{item.name}}</span>
            <i class="el-icon-circle-close el-delete-icon" @click="colse(item)"></i>
          </div>
        </div>
      </el-card>
    </div>
  </div>
</template>

<script>
import { BaseComponent } from "@/mixins.js";
import ItemDiaplsy from "../form/ItemDiaplsy";
import ColumnDisplay from "./ColumnDisplay";
import Actions from "./Actions/Index";
export default {
  mixins: [BaseComponent],
  components: {
    ItemDiaplsy,
    ColumnDisplay,
    Actions
  },
  props: {
    value: Array,
    attrs: Object
  },
  data() {
    return {
      loading: false, //是否加载
      tableData: [], //表格数据
      pageData: {
        pageSize: this.attrs.perPage,
        total: 0,
        currentPage: 1,
        lastPage: 1,
      },
      page: 1, //当前页
      filterFormData: null, //表单搜索数据
      quickSearch: null, //快捷搜索内容
      // attrs: {
      //   method: "get",
      //   dataUrl: "http://deep-admin:8888/admin-api/home/column",
      //   filter: {
      //     filters: [
      //       {
      //         column: "name",
      //         defaultValue: null,
      //         exprFormat: "%{value}%",
      //         is_filter_null: false,
      //         label: "栏目名称",
      //         operator: "like",
      //         component: {
      //           append: null,
      //           autocomplete: "off",
      //           autofocus: false,
      //           autosize: false,
      //           className: null,
      //           clearable: true,
      //           componentName: "Input",
      //           componentValue: "",
      //           disabled: false,
      //           form: null,
      //           label: null,
      //           max: null,
      //           maxlength: null,
      //           min: null,
      //           minlength: null,
      //           placeholder: null,
      //           prefixIcon: null,
      //           prepend: null,
      //           readonly: false,
      //           ref: null,
      //           refData: null,
      //           resize: null,
      //           rows: 2,
      //           showPassword: false,
      //           showWordLimit: false,
      //           size: null,
      //           step: null,
      //           style: "width:120px;margin-left:5px;",
      //           suffixIcon: null,
      //           tabindex: null,
      //           type: "text",
      //           validateEvent: true,
      //         },
      //       },
      //     ],
      //   },
      //   showActions: true,
      //   attributes: {
      //     actionAlign: "left",
      //     actionFixed: "right",
      //     actionLabel: "操作",
      //     actionWidth: 150,
      //     border: false,
      //     dataVuex: false,
      //     defaultExpandAll: false,
      //     draggable: false,
      //     draggableUrl: null,
      //     emptyText: "暂无数据",
      //     fit: true,
      //     height: 400,
      //     hideActions: false,
      //     highlightCurrentRow: true,
      //     maxHeight: null,
      //     rowKey: "id",
      //     selection: false,
      //     showHeader: true,
      //     showSummary: true,
      //     size: "",
      //     stripe: true,
      //     tooltipEffect: null,
      //     topTool: true,
      //     treeProps: {
      //       children: "children",
      //       hasChildren: "hasChildren",
      //     },
      //   },
      //   columnAttributes: [
      //     // {
      //     //   align: "center",
      //     //   className: null,
      //     //   columnKey: "id",
      //     //   displayComponentAttrs: null,
      //     //   filterMultiple: true,
      //     //   filterPlacement: null,
      //     //   filters: [],
      //     //   fixed: null,
      //     //   headerAlign: null,
      //     //   help: null,
      //     //   itemPrefix: "",
      //     //   itemSuffix: "",
      //     //   label: "序号",
      //     //   labelClassName: null,
      //     //   minWidth: null,
      //     //   prop: "id",
      //     //   showOverflowTooltip: null,
      //     //   sortable: true,
      //     //   type: null,
      //     //   width: 120,
      //     // },
      //     {
      //       align: null,
      //       className: null,
      //       columnKey: "name",
      //       displayComponentAttrs: null,
      //       filterMultiple: true,
      //       filterPlacement: null,
      //       filters: [],
      //       fixed: null,
      //       headerAlign: null,
      //       help: null,
      //       itemPrefix: "",
      //       itemSuffix: "",
      //       label: "栏目名称",
      //       labelClassName: null,
      //       minWidth: "120",
      //       prop: "name",
      //       showOverflowTooltip: null,
      //       sortable: null,
      //       type: null,
      //       width: null,
      //     },
      //   ],
      // },
      seleted: null,
      selectList: [],
      selectionRows: [],
      timer: null
    };
  },
  watch: {
    async value (newValue, oldValue) {
      if (newValue !== oldValue) {
        if ( newValue && newValue.length > 0 && typeof(newValue[0]) == 'number') {
          if (!this.attrs.isMultiple) {
            // 单选
            this.seleted = newValue[0];
            this.fadeIn('1')
          } else {
            // 多选
            this.fadeIn('2')
          }
        } 
      }
    }
  },
  mounted() {
    console.log("this.attrs", this.attrs);
    this.filterFormData = this._.cloneDeep(this.attrs.filter.filterFormData);
    this.getData()
  },
  updated() {
    this.$nextTick(() => {});
  },
  destroyed() {
    //取消监听
    try {
    } catch (e) {}
  },
  methods: {
    //表单还原
    onFilterReset() {
      this.filterFormData = this._.cloneDeep(this.attrs.filter.filterFormData);
      this.quickSearch = null;
      // deep admin start
      this.page = 1;
      // deep admin end
      this.getData();
    },
    //获取数据
    getData() {
      this.loading = true;
      this.$http[this.attrs.method](this.attrs.dataUrl, {
        params: {
          get_data: true,
          page: this.page,
          per_page: this.pageData.pageSize,
          ...this.sort,
          ...this.filterFormData,
          ...this.$route.query,
        },
      })
        .then(({ data }) => {
          if (!this.attrs.hidePage) {
            this.tableData = data.data;
            this.pageData.pageSize = data.per_page;
            this.pageData.currentPage = data.current_page;
            this.pageData.total = data.total;
            this.pageData.lastPage = data.last_page;
            this.loading = false;

            this.$store.commit("setGridData", { key: "sort", data: this.sort });
            this.$store.commit("setGridData", { key: "page", data: this.page });
            this.$store.commit("setGridData", {
              key: "pageData",
              data: this.pageData,
            });
          } else {
            this.tableData = data;
          }

          //**保存 Grid状态 */
          if (this.attrs.attributes.dataVuex) {
            this.$store.commit("setGridData", {
              key: "tableData",
              data: this.tableData,
            });
          }

          this.$store.commit("setGridData", {
            key: "quickSearch",
            data: this.quickSearch,
          });
          //deep admin start
          this.$store.commit("setGridData", {
            key: "quickFilter",
            data: this.quickFilter,
          });
          //deep admin end
          this.$store.commit("setGridData", {
            key: "filterFormData",
            data: this.filterFormData,
          });
          /** */
        })
        .finally(() => {
          this.loading = false;
        });
    },
    // 递归获取数据
    fadeIn(type) {
      if (this.tableData && this.tableData.length > 0) {
        clearTimeout(this.timer)
        if (type == 1) {
          // 单选
          this.selectList = this.tableData;
          this.$emit("change", this.tableData);
        } else {
          // 多选
          this.selectionRows = this.tableData;
          this.selectList = this.tableData;
          this.$emit("change", this.tableData);
          // 勾选数据
          this.tableData.forEach(row => {
            this.$refs.multipleTable.toggleRowSelection(row);
          });
        }
      } else {
        this.timer = setTimeout(()=> {
          this.fadeIn(type)
        }, 500)
      }
    },
    //每页大小改变时
    onPageSizeChange(per_page) {
      this.page = 1;
      this.pageData.pageSize = per_page;
      this.getData();
    },
    //页码改变时
    onPageCurrentChange(page) {
      this.page = page;
      this.getData();
    },
    //表单过滤提交
    onFilterSubmit() {
      // deep admin start
      this.page = 1;
      // deep admin end
      this.getData();
    },
    //当选择项发生变化时会触发该事件
    onTableselectionChange(selection) {
      this.selectionRows = selection;
      this.selectList = selection;
      this.$emit("change", selection);
    },
    downMove(sort) {
      this.tableData = this.tableData.sort(this.compare('sort',false));
      // 当前操作项index
      let index = this.tableData.findIndex(function(item) {
          return item.sort == sort;
      });
      // sort属性index
      let cIndex = this.attrs.columnAttributes.findIndex(function(item) {
          return item.columnKey === 'sort';
      });
      let downItem = this.tableData[index + 1];
      let curItem = this.tableData[index]
      // 当前页最后一项下移重新加载列表
      if (index + 1 === this.tableData.length) {
        this.setSort(this.attrs.columnAttributes[cIndex].displayComponentAttrs.setSortAction, 'down', curItem.id, '', true)
      } else {
        this.tableData[index].sort = this.tableData[index+1].sort
        this.tableData[index+1].sort = sort
        this.tableData[index + 1] = this.tableData[index];
        this.tableData[index] = downItem;
        this.$set(this.tableData, index+1, curItem);
        this.$set(this.tableData, index, downItem);
        this.setSort(this.attrs.columnAttributes[cIndex].displayComponentAttrs.setSortAction, 'down', curItem.id, downItem.id, false)
      }
    },
    upMove(sort) {
      this.tableData = this.tableData.sort(this.compare('sort',false));
      // 当前操作项index
      let index = this.tableData.findIndex(function(item) {
          return item.sort == sort;
      });
      // sort属性index
      let cIndex = this.attrs.columnAttributes.findIndex(function(item) {
          return item.columnKey === 'sort';
      });
      let downItem = this.tableData[index - 1];
      let curItem = this.tableData[index]
      // 当前页第一项上移重新加载列表
      if (index === 0) {
        this.setSort(this.attrs.columnAttributes[cIndex].displayComponentAttrs.setSortAction, 'up', curItem.id, '', true)
      } else {
        this.tableData[index].sort = this.tableData[index-1].sort
        this.tableData[index-1].sort = sort
        this.tableData[index - 1] = this.tableData[index];
        this.tableData[index] = downItem;
        this.$set(this.tableData, index-1, curItem);
        this.$set(this.tableData, index, downItem);
        this.setSort(this.attrs.columnAttributes[cIndex].displayComponentAttrs.setSortAction, 'up', curItem.id, downItem.id, false)
      }
    },
    // 单选
    handleSelectionChange(val){
      if(val){
        this.seleted = val.id;
        this.selectList = [val];
        this.$emit("change", [val]);
      }
    },
    // 删除选择项
    colse(item) {
      // 先判断是单选还是多选
      if (this.attrs.isMultiple) {
        var newList = JSON.parse(JSON.stringify(this.selectList))
        // 多选
        var newSelectList = newList.filter(citem => citem.id != item.id)
        this.selectList = newSelectList

        // 清空数据
        var index = this.tableData.findIndex(citem => citem.id == item.id)
        this.$refs.multipleTable.toggleRowSelection(this.tableData[index]);

        this.$emit("change", newSelectList);
      } else {
        // 单选
        this.seleted = null;
        this.selectList = [];
        this.$emit("change", []);
      }
    }
  },
  computed: {},
};
</script>

<style lang="scss">
.grid-container {
  .bottom-border {
    border-bottom: 1px solid #ebeef5;
  }
  .grid-top-container-wrap {
    padding: 8px;
    display: flex;
    justify-content: space-between;
    min-height: 32px;
  }
  .grid-top-container {
    padding: 8px;
    display: flex;
    justify-content: space-between;
    min-height: 32px;
    flex: 1;
    .grid-top-container-left {
      display: flex;
      align-items: center;
      margin-right: 5px;
      flex: 1;
      .flex-all {
        flex: 1;
      }
    }
    .grid-top-container-right {
      display: flex;
      align-items: center;
      .icon-actions {
        display: flex;
        align-items: center;
        margin-left: 5px;
        i {
          font-size: 20px;
          margin-right: 10px;
        }
      }
    }
  }
  .el-tabs__header {
    padding: 0;
    margin: 0;
  }
  .el-tabs__item {
    padding: 0 15px;
    height: 50px;
    line-height: 50px;
  }
  .el-tabs--top .el-tabs__item.is-top:nth-child(2) {
    padding-left: 15px;
  }
  .el-tabs__nav-wrap::after {
    height: 1px;
    background-color: #ebeef5;
  }
  .filter-form {
    padding: 10px;
    background-color: #ffffff;
    .el-form-item {
      margin-bottom: 0px;
      .el-form-item__label {
        padding: 0;
      }
    }
  }
  .filter-form-style-center {
    .el-form {
      display: flex;
      justify-content: center;
    }
  }
  .left-tyle {
    background: white;
    margin-right: 5px;
    .el-form-item {
      margin-top: 5px;
    }
  }
}
.rowForm {
  width: 100%;
  display: flex !important;
  .el-form-item__content {
    flex: 1;
  }
}
.right {
  justify-content: space-between !important;
}
.grid-container .el-tabs__nav-wrap::after {
  height: 0px !important;
}
.form-bottom {
  margin-bottom: 4px !important;
}
// 单选或多选的显示
.select-wrap {
  display: flex;
  margin: 4px;
}

.select-info {
  padding: 0px 4px;
  background: #e7e4e4;
  margin-right: 10px;
  border-radius: 4px;
  display: flex;
  align-items: center;
}
.el-delete-icon {
  font-size: 16px;
  margin-left: 4px;
  cursor: pointer;
}
</style>