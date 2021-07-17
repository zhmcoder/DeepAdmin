<template>
    <div class="property-list">
        <!-- <el-form ref="ruleForm" :model="value" label-width="0px" class="demo-ruleForm" > -->
        <el-form-item>
            <el-table :data="tableData" style="width: 50%" :show-header="attrs.is_title==1">
                <el-table-column v-for="(col, i) in columns" :key="i" :prop="col.prop" :width="Number(col.width) + 20 + 'px'">
                    <template slot="header">
                        <span>{{col.label}}</span>
                    </template>
                    <template slot-scope="scope">
                        <component
                            v-model="scope.row[col.prop]"
                            v-if="col.component"
                            :is="col.component.componentName"
                            :attrs="col.component"
                            @change="onChange"
                        />
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
        <el-form-item>
            <el-button style="display:block;margin: 0 auto;" type="text" size="middle" @click.native.prevent="addRow">添加</el-button>
            <!-- <el-button @click="submitData">提交</el-button> -->
        </el-form-item>
        <!-- </el-form> -->
    </div>
</template>
<script>
    export default {
        props: ["attrs", "value"],
        data() {
            return {
                columns: [],
                tableData: this.value
            }
        },
        created() {
            this.columns = this.attrs.columns;
            this.tableData = this.attrs.data.length <= 0 ? [{}] : this.attrs.data;
            console.log('table data');
            console.log(this.tableData);
            this.$emit("change", this.tableData)
        },
        methods: {
            onChange(value) {
                console.log('change');
                console.log(value);
                this.$emit("change", this.tableData);
            },
            // 增加
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
            submitData(){
              console.log(this.tableData)
              console.log('this.attr_names',this.attr_names);
              console.log('this.tableData',this.tableData);
            }
        }
    };
</script>
<style lang="scss" scoped>
    .property-list {
        width: 80%;
    }
</style>
