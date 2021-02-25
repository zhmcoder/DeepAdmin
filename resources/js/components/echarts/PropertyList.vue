<template>
    <div class="property-list">
      <el-form ref="ruleForm" label-width="0px" class="demo-ruleForm" >
        <el-form-item>
          <el-table :data="tableData" style="width: 100%">
                <el-table-column v-for="(col, i) in attr_names" :key="i" :prop="col.prop">
                  <template slot="header">
                    <span>{{col.label}}</span>
                  </template>
                  <template slot-scope="scope">
                    <el-input v-if="col.type=='input'" size="mini" v-model="scope.row[col.prop]"> </el-input>
                    <el-select v-if="col.type=='select'" v-model="scope.row[col.prop]">
                      <el-option
                        v-for="item in col.options"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value">
                      </el-option>
                    </el-select>
                  </template>
                </el-table-column>
                <el-table-column fixed="right" label="操作" width="80">
                  <template slot-scope="scope">
                    <el-button
                      @click.native.prevent="deleteRow(scope.$index, tableData)"
                      type="text"
                      size="small">
                      移除
                    </el-button>
                  </template>
                </el-table-column>
          </el-table>
        </el-form-item>
        <el-form-item required>
          <el-button @click="addRow">添加</el-button>
          <el-button @click="submitData">提交</el-button>
        </el-form-item>
      </el-form>
    </div>
</template>
<script>
  export default {
    props: {
        attrs: Object
    },
    data() {
      return {
        attr_names: [],
        tableData:[{}]
      }
    },
    created() {
      this.attr_names = this.attrs.data.attr_names
      this.tableData = this.attrs.data.tableData
    },
    methods: {
      // 增加行
      addRow(){
        let obj={}
        this.tableData.push(obj);
      },
      //删除行
      deleteRow(index, rows) {
        rows.splice(index, 1);
      },
      // 调试代码信息用
      submitData(){
        console.log('this.attr_names',this.attr_names);
        console.log('this.tableData',this.tableData);
      }
    }
  };
</script>
<style lang="scss" scoped>
.property-list {
  height: 360px;
  overflow: auto;
  width: 100%;
  padding: 20px;
}
</style>
