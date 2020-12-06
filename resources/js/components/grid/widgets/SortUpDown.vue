<template slot-scope="scope">
    <div>
        <el-button style="width: 25px; height: 30px; text-align: center;padding: 2px"
              @click="move_up">
            <i class="el-icon-arrow-up"></i>
        </el-button>
        <el-button style="width: 25px; height: 30px; text-align: center;padding: 2px"
             @click="move_down">
            <i class="el-icon-arrow-down"></i>
        </el-button>
    </div>
</template>
<script>
    export default {
        props: {
            attrs: Object,
            row: Object,
            column_value: {
                default: null
            },
            value: {
                default: null
            }
        },
        data() {
            return {
                sort_value: this.value,
                sort_page_action: this.attrs.sortAction,
                sort_action: this.attrs.pageSortAction
            };
        },
        mounted() {
            // console.log('attrs', this.attrs);

        },
        methods: {
            move_down() {
                this.$emit('moveDown', this.row.sort)
            },
            move_up() {
                this.$emit('moveUp', this.row.sort)
            },
        }
    }
</script>
<style lang="scss" scoped>
    .rank {
        input {
            width: 30px;
            display: inline-block;
            border: #4e555b;
            outline: none;
        }
    }

</style>

