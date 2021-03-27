<template>
    <div class="property-list">
      <!-- <el-form ref="ruleForm" :model="value" label-width="0px" class="demo-ruleForm" > -->
        <el-form-item>
          <el-table :data="tableData" style="width: 100%" :show-header="attrs.data.is_title==1">
                <el-table-column v-for="(col, i) in attr_names" :key="i" :prop="col.prop" :width="Number(col.width) + 20 + 'px'">
                  <template slot="header">
                    <span>{{col.label}}</span>
                  </template>
                  <template slot-scope="scope">
                    <el-input :style="`width: ${col.width}px;`" v-if="col.type=='input'" size="mini" v-model="scope.row[col.prop]" @input="changeInput"> </el-input>
                    <el-select :style="`width: ${col.width}px;`" v-if="col.type=='select'" v-model="scope.row[col.prop]" @input="changeInput">
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
                    <el-button v-if="scope.$index==0" type="text" size="small" @click.native.prevent="addRow">添加</el-button>
                    <el-button v-else
                      @click.native.prevent="deleteRow(scope.$index, tableData)"
                      type="text"
                      size="small">
                      移除
                    </el-button>
                  </template>
                </el-table-column>
          </el-table>
        </el-form-item>
        <!-- <el-form-item required>
          <el-button @click="addRow">添加</el-button>
          <el-button @click="submitData">提交</el-button>
        </el-form-item> -->
      <!-- </el-form> -->
    </div>
</template>
<script>
  export default {
    props: ["attrs", "value"],
    data() {
      return {
        attr_names: [],
        tableData: this.value
      }
    },
    created() {
      this.attr_names = this.attrs.data.attr_names
      this.tableData = this.attrs.data.tableData.length <= 0 ? [{}] : this.attrs.data.tableData
    },
    methods: {
      changeInput(value) {
        this.$emit("change", this.tableData)
      },
      // 增加行
      addRow(){
        let obj={}
        this.tableData.push(obj);
      },
      //删除行
      deleteRow(index, rows) {
        if (this.tableData.length > 1) {
          rows.splice(index, 1);
        }
      },
      // 调试代码信息用
      // submitData(){
      //   console.log(this.tableData)
      //   console.log('this.attr_names',this.attr_names);
      //   console.log('this.tableData',this.tableData);
      // }
    }
  };
</script>
<style lang="scss" scoped>
.property-list {
  width: 100%;
}
</style>
