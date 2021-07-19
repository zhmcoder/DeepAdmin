<template slot-scope="props">
    <el-form class="demo-table-expand">
        <el-form-item v-for="(item, i) in info_list" :key="i" :label="item.label">
            <span class="text_value">{{item.name}}</span>
        </el-form-item>
    </el-form>
</template>item
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
        width: 50%;
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
            }
        },
        created() {
            this.info_list = this.attrs.info_list;
        },
    }
</script>
