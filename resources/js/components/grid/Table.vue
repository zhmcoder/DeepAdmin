<template>
  <div class="grid-container">
    <div ref="topView">
      <component
        v-if="attrs.top"
        :is="attrs.top.componentName"
        :attrs="attrs.top"
      />
      <el-card
        shadow="never"
        :body-style="{ padding: 0 }"
        class="margin-bottom-sm"
        v-if="attrs.filter.filters.length > 0"
      >
        <div
          class="filter-form"
          :class="{ 'filter-form-style-center': attrs.filterFormCenter }"
        >
          <el-form :inline="true" :model="filterFormData" v-if="filterFormData">
            <el-form-item v-if="attrs.quickSearch">
              <el-input
                v-model="quickSearch"
                :placeholder="attrs.quickSearch.placeholder"
                :clearable="true"
                @clear="getData"
                @keyup.enter.native="getData"
              ></el-input>
            </el-form-item>
            <template v-for="(item, index) in attrs.filter.filters">
              <!-- 单独展示一行 -->
              <el-row v-if="item.component.isRow" :key="index">
                <el-col :span="24">
                  <el-form-item
                    class="rowForm form-bottom"
                    :key="index"
                    :label="item.label"
                  >
                    <ItemDiaplsy
                      v-model="filterFormData[item.column]"
                      :form-item="item"
                      :form-items="attrs.filter.filters"
                      :form-data="filterFormData"
                      from="table"
                    />
                  </el-form-item>
                </el-col>
              </el-row>
              <el-form-item
                v-else
                :key="index"
                :label="item.label"
                class="form-bottom"
              >
                <ItemDiaplsy
                  v-model="filterFormData[item.column]"
                  :form-item="item"
                  :form-items="attrs.filter.filters"
                  :form-data="filterFormData"
                  from="table"
                />
              </el-form-item>
            </template>
            <!-- <el-form-item
              v-for="(item, index) in attrs.filter.filters"
              :key="index"
              :label="item.label"
            >
              <ItemDiaplsy
                v-model="filterFormData[item.column]"
                :form-item="item"
                :form-items="attrs.filters"
                :form-data="filterFormData"
              />
            </el-form-item> -->
            <el-form-item>
              <el-button v-if="attrs.filterSearchLabel" type="primary" @click="onFilterSubmit">{{
                attrs.filterSearchLabel ? attrs.filterSearchLabel : "搜索"
              }}</el-button>
              <el-button v-if="attrs.filterResetLabel" @click="onFilterReset">{{
                attrs.filterResetLabel ? attrs.filterResetLabel : "重置"
              }}</el-button>
              <el-button
                v-if="attrs.filter.exportUri"
                :loading="export1Loading"
                @click="() => onFilterExport(1)"
              >
                {{ attrs.filter.exportUriText }}
              </el-button>
              <el-button
                v-if="attrs.filter.exportPdf"
                :loading="export2Loading"
                @click="() => onFilterExport(2)"
              >
                {{ attrs.filter.exportPdfText }}
              </el-button>
              <el-button
                v-if="attrs.filter.exportImg"
                :loading="export3Loading"
                @click="() => onFilterExport(3)"
              >
                {{ attrs.filter.exportImgText }}
              </el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-card>
    </div>

    <el-card shadow="never" :body-style="{ padding: 0 }" v-loading="loading">
      <div class="bottom-border" ref="toolbarsView" v-if="attrs.toolbars.show">
        <div class="grid-top-container-wrap">
          <div class="grid-top-container-tabs" v-if="attrs.tabFilter">
            <el-tabs v-model="tabFilter" @tab-click="handleClick">
              <el-tab-pane
                v-for="(item, index) in attrs.tabFilter.options"
                :key="index"
                :label="item.title"
                :name="setName(item.label)"
              >
              </el-tab-pane>
            </el-tabs>
          </div>
          <div class="grid-top-container">
            <div class="grid-top-container-left">
              <BatchActions
                :routers="attrs.routers"
                :key_name="attrs.keyName"
                :rows="selectionRows"
                :actions="attrs.batchActions"
                v-if="attrs.selection"
              />
              <div
                class="search-view mr-10"
                v-if="attrs.quickSearch && attrs.filter.filters.length <= 0"
              >
                <el-input
                  v-model="quickSearch"
                  :placeholder="attrs.quickSearch.placeholder"
                  :clearable="true"
                  @clear="getData"
                  @keyup.enter.native="getData"
                >
                  <el-button
                    @click="getData"
                    :loading="loading"
                    slot="append"
                    >{{
                      attrs.filterSearchLabel ? attrs.filterSearchLabel : "搜索"
                    }}</el-button
                  >
                </el-input>
              </div>
              <div
                class="flex-c flex-all"
                :class="
                  attrs.quickFilter && attrs.quickFilter.position == 'right'
                    ? 'right'
                    : ''
                "
              >
                <div class="flex-c">
                  <component
                    v-for="(component, index) in attrs.toolbars.left"
                    :key="component.componentName + index"
                    :is="component.componentName"
                    :attrs="component"
                  />
                </div>

                <el-radio-group
                  v-if="attrs.quickFilter"
                  v-model="quickFilter"
                  :style="attrs.quickFilter.style"
                  :class="attrs.quickFilter.className"
                  :disabled="attrs.quickFilter.disabled"
                  @change="getData"
                >
                  <el-radio-button
                    v-if="attrs.quickFilter"
                    v-for="(radio, index) in attrs.quickFilter.options"
                    :style="radio.style"
                    :class="radio.className"
                    :key="index"
                    :label="radio.label"
                    :disabled="radio.disabled"
                    :border="radio.border"
                    :size="radio.size"
                    >{{ radio.title }}</el-radio-button
                  >
                </el-radio-group>
              </div>
            </div>
            <div class="grid-top-container-right">
              <component
                v-for="(component, index) in attrs.toolbars.right"
                :key="component.componentName + index"
                :is="component.componentName"
                :attrs="component"
                :filterData="filterFormData"
              />
              <!-- deep-admin filterData -->
              <template v-if="attrs.attributes.topTool">
                <el-divider
                  direction="vertical"
                  v-if="!attrs.attributes.hideCreateButton"
                ></el-divider>
                <div class="icon-actions">
                  <el-dropdown trigger="click">
                    <el-tooltip
                      class="item"
                      effect="dark"
                      content="密度"
                      placement="top"
                    >
                      <i class="el-icon-rank hover"></i>
                    </el-tooltip>
                    <el-dropdown-menu slot="dropdown">
                      <a @click="attrs.attributes.size = null">
                        <el-dropdown-item>正常</el-dropdown-item>
                      </a>
                      <a @click="attrs.attributes.size = 'medium'">
                        <el-dropdown-item>中等</el-dropdown-item>
                      </a>
                      <a @click="attrs.attributes.size = 'small'">
                        <el-dropdown-item>紧凑</el-dropdown-item>
                      </a>
                      <a @click="attrs.attributes.size = 'mini'">
                        <el-dropdown-item>迷你</el-dropdown-item>
                      </a>
                    </el-dropdown-menu>
                  </el-dropdown>

                  <el-tooltip
                    class="item"
                    effect="dark"
                    content="刷新"
                    placement="top"
                  >
                    <i class="el-icon-refresh hover" @click="getData"></i>
                  </el-tooltip>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
      <div>
        <el-table
          :ref="attrs.ref || 'table'"
          :data="tableData"
          :row-key="attrs.attributes.rowKey"
          :height="gridHeight"
          :show-summary="attrs.attributes.showSummary"
          :summary-method="total_info ? getSummaries : null"
          :max-height="attrs.attributes.maxHeight"
          :default-sort="default_sort_get"
          :stripe="attrs.attributes.stripe"
          :border="attrs.attributes.border"
          :size="attrs.attributes.size"
          :fit="attrs.attributes.fit"
          :show-header="attrs.attributes.showHeader"
          :highlight-current-row="attrs.attributes.highlightCurrentRow"
          :empty-text="attrs.attributes.emptyText"
          :tooltip-effect="attrs.attributes.tooltipEffect"
          :default-expand-all="attrs.attributes.defaultExpandAll"
          @sort-change="onTableSortChange"
          @selection-change="onTableselectionChange"
          :span-method="objectSpanMethod"
        >
          <el-table-column
            v-if="attrs.attributes.selection"
            :selectable="selectEnable"
            align="center"
            type="selection"
          ></el-table-column>
          <el-table-column
            v-if="attrs.tree"
            align="center"
            width="80"
          ></el-table-column>
          <template v-for="column in attrs.columnAttributes">
            <template>
              <el-table-column
                type="index"
                :key="column.prop + 1"
                :index="indexAdd"
                v-if="column.type == 'index'"
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
              </el-table-column>
              <el-table-column
                v-else
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
      </div>
      <div class="table-page padding-xs" v-if="!attrs.hidePage">
        <el-pagination
          :layout="attrs.pageLayout"
          :hide-on-single-page="false"
          :total="pageData.total"
          :page-size="pageData.pageSize"
          :current-page="pageData.currentPage"
          :page-sizes="attrs.pageSizes"
          :background="attrs.pageBackground"
          @size-change="onPageSizeChange"
          @current-change="onPageCurrentChange"
        />
      </div>
    </el-card>
    <component
      v-if="attrs.bottom"
      :is="attrs.bottom.componentName"
      :attrs="attrs.bottom"
    />
    <DialogForm
      ref="DialogGridFrom"
      v-if="attrs.isDialogForm"
      :dialogFormWidth="getDialogFormData(1)"
      :dialogForm="getDialogFormData(2)"
      :dialogTitle="getDialogFormData(3)"
      :dialogTitleCenter="attrs.dialogTitleCenter"
    />
    <DrawerForm
      ref="DialogGridFrom"
      v-if="attrs.isDrawerForm"
      :dialogFormWidth="getDialogFormData(1)"
      :dialogForm="getDialogFormData(2)"
      :dialogTitle="getDialogFormData(3)"
      :dialogTitleCenter="attrs.dialogTitleCenter"
    />
  </div>
</template>

<script>
import { BaseComponent } from "@/mixins.js";
import { mapState } from "vuex";
import ColumnDisplay from "./ColumnDisplay";
import Actions from "./Actions/Index";
import BatchActions from "./BatchActions/Index";
import ItemDiaplsy from "../form/ItemDiaplsy";
import DialogForm from "./DialogForm";
import DrawerForm from "./DrawerForm";
import SelectMenu from "../widgets/Form/SelectMenu.vue";
export default {
  mixins: [BaseComponent],
  components: {
    ColumnDisplay,
    Actions,
    ItemDiaplsy,
    BatchActions,
    DialogForm,
    DrawerForm,
    SelectMenu,
  },
  props: {
    attrs: Object,
  },
  data() {
    return {
      loading: false, //是否加载
      sort: {}, //排序对象
      tableData: [], //表格数据
      pageData: {
        pageSize: this.attrs.perPage,
        total: 0,
        currentPage: 1,
        lastPage: 1,
      },
      page: 1, //当前页
      quickSearch: null, //快捷搜索内容
      quickFilter: "", //快捷筛选内容 <!--deep admin-->
      tabFilter: "",
      selectionRows: [], //已选择的row
      filterFormData: null, //表单搜索数据
      tabsSelectdata: {},
      tabsActiveName: "all",
      topViewHeight: 0,
      toolbarsViewHeight: 0,
      addOrEdit: "", //点击的action是否是添加或修改
      //合并表格
      columnArr: [],
      spanArr: [], //临时组
      spanData: [], // 组合的合并组
      export1Loading: false,
      export2Loading: false,
      export3Loading: false,
      total_info: null
    };
  },
  mounted() {
    //初始化默认设置值
    this.filterFormData = this._.cloneDeep(this.attrs.filter.filterFormData);
    this.columnArr = this.attrs.attributes.spanColumns || [];
    this.sort = this._.cloneDeep(this.attrs.defaultSort);
    //deep admin start
    if (this.attrs.quickFilter) {
      this.quickFilter = this._.cloneDeep(this.attrs.quickFilter.defaultValue);
    }
    //tab搜索的默认值
    if (this.attrs.tabFilter) {
      this.tabFilter = String(
        this._.cloneDeep(this.attrs.tabFilter.defaultValue)
      );
    }
    //deep admin end
    //初始化vuex状态值
    if (this.$store.getters.thisPage.grids.page) {
      this.page = this._.cloneDeep(this.$store.getters.thisPage.grids.page);

      if (this.attrs.attributes.dataVuex) {
        this.tableData = this._.cloneDeep(
          this.$store.getters.thisPage.grids.tableData
        );
      }

      this.pageData = this._.cloneDeep(
        this.$store.getters.thisPage.grids.pageData
      );

      this.quickSearch = this._.cloneDeep(
        this.$store.getters.thisPage.grids.quickSearch
      );

      this.filterFormData = this._.cloneDeep(
        this.$store.getters.thisPage.grids.filterFormData
      );

      this.sort = this._.cloneDeep(this.$store.getters.thisPage.grids.sort);
    }

    //加载数据
    if (this.tableData.length <= 0 || !this.attrs.attributes.dataVuex) {
      this.getData();
    }

    //监听事件
    this.$bus.on("tableReload", () => {
      this.getData();
    });
    this.$bus.on("tableSetLoading", (status) => {
      this.loading = status;
    });

    this.$bus.on("showDialogGridFrom", ({ isShow, key, addOrEdit }) => {
      this.addOrEdit = addOrEdit || this.addOrEdit;
      if (this.$refs["DialogGridFrom"]) {
        this.$refs["DialogGridFrom"].dialogVisible = isShow;
        this.$refs["DialogGridFrom"].key = key;
      }
    });
    this.$bus.on("showDialogGridFrom1", ({ isShow, key, addOrEdit }) => {
      console.log("点击点击====");
      this.addOrEdit = addOrEdit || this.addOrEdit;
      if (this.$refs["DialogGridFrom"]) {
        this.$refs["DialogGridFrom"].dialogVisible = isShow;
        this.$refs["DialogGridFrom"].key = key;
      }
    });

    // 监听刷新页面
    this.$bus.on("reloadGridFrom", () => {
      if (this.attrs.isReload) {
        this.$bus.emit("pageReload");
      }
    });

    this.$nextTick(() => {
      this.topViewHeight = this.$refs.topView.offsetHeight;
      this.toolbarsViewHeight = this.$refs.toolbarsView.offsetHeight;
    });
  },
  updated() {
    this.$nextTick(() => {
      this.$refs.table.doLayout();
    });
    this.$bus.on("showDialogGridFrom", ({ isShow, key, addOrEdit }) => {
      this.addOrEdit = addOrEdit || this.addOrEdit;
      if (this.$refs["DialogGridFrom"]) {
        this.$refs["DialogGridFrom"].dialogVisible = isShow;
        this.$refs["DialogGridFrom"].key = key;
      }
    });
    this.$bus.on("showDialogGridFrom1", ({ isShow, key, addOrEdit }) => {
      console.log("点击点击====");
      this.addOrEdit = addOrEdit || this.addOrEdit;
      if (this.$refs["DialogGridFrom"]) {
        this.$refs["DialogGridFrom"].dialogVisible = isShow;
        this.$refs["DialogGridFrom"].key = key;
      }
    });
  },
  beforeDestroy() {
    var is_reload = this.$route.query.is_reload;
    if (is_reload == 1) {
      this.onFilterReset();
    }
  },
  destroyed() {
    //取消监听
    try {
      this.$bus.off("tableReload");
      this.$bus.off("tableSetLoading");
      this.$bus.off("showDialogGridFrom");
    } catch (e) {}
  },
  methods: {
    getSummaries(param) {
      const { columns, data } = param;
      const sums = [];
      columns.forEach((column, index) => {
        if (index === 0) {
          sums[index] = '合计';
          return;
        } else {
          if(this.total_info) {
            sums[index] = this.total_info[column.property] ? String(this.total_info[column.property]) : '';
            return
          }
        }
      });
      return sums;
    },
    // 计算需要合并的单元格
    getSpanData(data) {
      this.spanData = [];
      this.columnArr.forEach((element) => {
        let contactDot = 0;
        this.spanArr = [];
        data.forEach((item, index) => {
          if (index === 0) {
            this.spanArr.push(1);
          } else {
            if (item[element] === data[index - 1][element]) {
              this.spanArr[contactDot] += 1;
              this.spanArr.push(0);
            } else {
              contactDot = index;
              this.spanArr.push(1);
            }
          }
        });
        this.spanData.push(this.spanArr);
      });
    },
    objectSpanMethod({ row, column, rowIndex, columnIndex }) {
      // console.log('this.spanData', this.spanData)
      // console.log('this.columnArr', this.columnArr)
      // console.log('columnIndex', columnIndex)
      // console.log('rowIndex', rowIndex)

      if (this.columnArr.includes(column.property)) {
        if (this.spanData[columnIndex][rowIndex]) {
          return {
            rowspan: this.spanData[columnIndex][rowIndex],
            colspan: 1,
          }
        } else {
          return {
            rowspan: 0,
            colspan: 0,
          }
        }
      }
    },
    selectEnable(row, rowIndex) {
      console.log("this.attrs.attributes", this.attrs.attributes);
      var attrs = this.attrs.attributes;
      // 不存在则保持原来的数据
      if (!attrs.selectionFiled) {
        return true;
      } else {
        if (attrs.selectionValue.indexOf(row[attrs.selectionFiled]) > -1) {
          return true;
        } else {
          return false;
        }
      }
    },
    // 自增id
    indexAdd(index) {
      return (
        index + 1 + (this.pageData.currentPage - 1) * this.pageData.pageSize
      );
    },
    // 更改为String类型
    setName(value) {
      return String(value);
    },
    // 切换tab状态
    handleClick(tab, event) {
      console.log(tab, event);
      this.getData();
    },
    onTabClick(e) {
      const name = this._.split(e.name, "----");
      this.tabsSelectdata[name[0]] = name[1];

      this.getData();
    },
    //表单过滤提交
    onFilterSubmit() {
      // deep admin start
      this.page = 1;
      // deep admin end
      this.getData();
    },
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
          ...this.q_search,
          ...this.quick_filter, //deep admin
          ...this.tab_filter,
          ...this.filterFormData,
          ...this.tabsSelectdata,
          ...this.$route.query,
        },
      })
        .then(({ data }) => {
          if (!this.attrs.hidePage) {
            this.tableData = data.data;
            this.getSpanData(this.tableData)
            this.pageData.pageSize = data.per_page;
            this.pageData.currentPage = data.current_page;
            this.pageData.total = data.total;
            this.pageData.lastPage = data.last_page;
            this.total_info = data.total_data;

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
          var is_reload = this.$route.query.is_reload;
          if (is_reload != 1) {
            this.$store.commit("setGridData", {
              key: "filterFormData",
              data: this.filterFormData,
            });
          } else {
            this.$store.commit("setGridData", {
              key: "filterFormData",
              data: this._.cloneDeep(this.attrs.filter.filterFormData),
            });
          }
          /** */
        })
        .finally(() => {
          this.loading = false;
        });
    },
    // 表单导出
    async onFilterExport(type) {
      var _this = this;
      if (type == 1) {
        this.export1Loading = true;
      } else if (type == 2) {
        this.export2Loading = true;
      } else if (type == 3) {
        this.export3Loading = true;
      }
      this.$http
        .post(
          type == 1 ? this.attrs.filter.exportUri : type == 2 ? this.attrs.filter.exportPdf : type == 3 ? this.attrs.filter.exportImg : '',
          {
            params: {
              get_data: true,
              page: this.page,
              per_page: this.pageData.pageSize,
              ...this.sort,
              ...this.q_search,
              ...this.quick_filter, //deep admin
              ...this.tab_filter,
              ...this.filterFormData,
              ...this.tabsSelectdata,
              ...this.$route.query,
            },
          }
        )
        .then(({ code, data, message }) => {
          if (code == 200) {
            if (type == 1) {
              _this.export1Loading = false;
            } else if (type == 2) {
              _this.export2Loading = false;
            } else if (type == 3) {
              _this.export3Loading = false;
            }
            if (data.action.down_url) {
              window.location.href = data.action.down_url;
            }
          } else {
            if (type == 1) {
              _this.export1Loading = false;
            } else if (type == 2) {
              _this.export2Loading = false;
            } else if (type == 3) {
              _this.export3Loading = false;
            }
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    //当表格的排序条件发生变化的时候会触发该事件
    onTableSortChange({ column, prop, order }) {
      if (order) {
        this.sort.sort_field = column.columnKey; //后端排序字段
        this.sort.sort_prop = column.property; //表格排序字段
        this.sort.sort_order = order == "ascending" ? "asc" : "desc";
      } else {
        this.sort = {};
      }
      this.getData();
    },
    //当选择项发生变化时会触发该事件
    onTableselectionChange(selection) {
      this.selectionRows = selection;
      this.$store.commit("setGridData", {
        key: "selectionKeys",
        data: this.keys,
      });
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
    downMove(sort) {
      this.tableData = this.tableData.sort(this.compare("sort", false));
      // 当前操作项index
      let index = this.tableData.findIndex(function (item) {
        return item.sort == sort;
      });
      // sort属性index
      let cIndex = this.attrs.columnAttributes.findIndex(function (item) {
        return item.columnKey === "sort";
      });
      let downItem = this.tableData[index + 1];
      let curItem = this.tableData[index];
      // 当前页最后一项下移重新加载列表
      if (index + 1 === this.tableData.length) {
        this.setSort(
          this.attrs.columnAttributes[cIndex].displayComponentAttrs
            .setSortAction,
          "down",
          curItem.id,
          "",
          true
        );
      } else {
        this.tableData[index].sort = this.tableData[index + 1].sort;
        this.tableData[index + 1].sort = sort;
        this.tableData[index + 1] = this.tableData[index];
        this.tableData[index] = downItem;
        this.$set(this.tableData, index + 1, curItem);
        this.$set(this.tableData, index, downItem);
        this.setSort(
          this.attrs.columnAttributes[cIndex].displayComponentAttrs
            .setSortAction,
          "down",
          curItem.id,
          downItem.id,
          false
        );
      }
    },
    upMove(sort) {
      this.tableData = this.tableData.sort(this.compare("sort", false));
      // 当前操作项index
      let index = this.tableData.findIndex(function (item) {
        return item.sort == sort;
      });
      // sort属性index
      let cIndex = this.attrs.columnAttributes.findIndex(function (item) {
        return item.columnKey === "sort";
      });
      let downItem = this.tableData[index - 1];
      let curItem = this.tableData[index];
      // 当前页第一项上移重新加载列表
      if (index === 0) {
        this.setSort(
          this.attrs.columnAttributes[cIndex].displayComponentAttrs
            .setSortAction,
          "up",
          curItem.id,
          "",
          true
        );
      } else {
        this.tableData[index].sort = this.tableData[index - 1].sort;
        this.tableData[index - 1].sort = sort;
        this.tableData[index - 1] = this.tableData[index];
        this.tableData[index] = downItem;
        this.$set(this.tableData, index - 1, curItem);
        this.$set(this.tableData, index, downItem);
        this.setSort(
          this.attrs.columnAttributes[cIndex].displayComponentAttrs
            .setSortAction,
          "up",
          curItem.id,
          downItem.id,
          false
        );
      }
    },
    compare(attr, rev) {
      //第二个参数没有传递 默认升序排列
      if (rev == undefined) {
        rev = 1;
      } else {
        rev = rev ? 1 : -1;
      }
      return function (a, b) {
        a = a[attr];
        b = b[attr];
        if (a < b) {
          return rev * -1;
        }
        if (a > b) {
          return rev * 1;
        }
        return 0;
      };
    },
    setSort(url, type, curId, changeId, refreshTable) {
      let param = {
        sort_type: type,
        current_id: curId,
      };
      if (changeId) {
        Object.assign(param, { change_id: changeId });
      }
      this.onRequest(url, param, refreshTable);
    },
    /**
     * uri 接口路径
     * params 请求参数
     * refreshTable 是否重新请求table数据
     */
    onRequest(uri, params, refreshTable) {
      this.$http.post(uri, params).then((res) => {
        if (res.code == 200) {
          if (refreshTable) {
            this.tableData = [];
            this.getData();
          }
        }
      });
    },
    /**
     * 获取DialogForm的展示数据
     * type 获取的类型数据--1:dialogFormWidth 2:dialogForm 3:dialogTitle
     */
    getDialogFormData(type) {
      let actionType = this.addOrEdit;
      if (type == 1) {
        if (actionType)
          return (
            this.attrs[actionType + "DialogFormWidth"] ||
            this.attrs.dialogFormWidth
          );
        return this.attrs.dialogFormWidth;
      } else if (type == 2) {
        if (actionType)
          return this.attrs[actionType + "DialogForm"] || this.attrs.dialogForm;
        return this.attrs.dialogForm;
      } else if (type == 3) {
        if (actionType)
          return (
            this.attrs[actionType + "DialogFormTitle"] || this.attrs.dialogTitle
          );
        return this.attrs.dialogTitle;
      }
    },
  },
  computed: {
    keys() {
      return this.selectionRows
        .map((item) => {
          return item[this.attrs.keyName];
        })
        .join(",");
    },
    //当前路径
    path() {
      return this.$route.path;
    },
    //
    columns() {
      return this.column_attributes.map((attributes) => {
        return attributes;
      });
    },
    //默认排序
    default_sort_get() {
      let defaultSort = {};
      // 有sort按sort排序
      if (this.attrs.columnAttributes.map((a) => a.prop).indexOf("sort") > -1) {
        defaultSort = {
          prop: "sort",
          order:
            this.sort && this.sort.sort_order == "asc"
              ? "ascending"
              : "descending",
        };
      } else if (this.sort) {
        defaultSort = {
          prop: this.sort.sort_prop,
          order: this.sort.sort_order == "asc" ? "ascending" : "descending",
        };
      }
      return defaultSort;
      // return this.sort
      //   ? {
      //       prop: this.sort.sort_prop,
      //       order: this.sort.sort_order == "asc" ? "ascending" : "descending",
      //     }
      //   : {};
    },
    //搜索处理
    q_search() {
      const q_search = new Object();
      this.attrs.quickSearch &&
        (q_search[this.attrs.quickSearch.searchKey] = this.quickSearch);
      return q_search;
    },
    // tabs
    tab_filter() {
      const tab_filter = new Object();
      this.attrs.tabFilter &&
        (tab_filter[this.attrs.tabFilter.filterKey] = this.tabFilter);
      return tab_filter;
    },
    //deep admin start
    quick_filter() {
      const quick_filter = new Object();
      this.attrs.quickFilter &&
        (quick_filter[this.attrs.quickFilter.filterKey] = this.quickFilter);
      return quick_filter;
    },
    //deep admin end
    gridHeight() {
      if (this.attrs.attributes.height == "auto") {
        return (
          window.innerHeight -
          55 -
          window.rootFooterHeight -
          (this.topViewHeight > 0 ? this.topViewHeight + 10 : 0) -
          25 -
          this.toolbarsViewHeight
        );
      }
      return this.attrs.attributes.height;
    },
  },
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
</style>

