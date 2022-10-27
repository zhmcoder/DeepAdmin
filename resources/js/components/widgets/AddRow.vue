<template>
  <div class="property-list">
    <!-- <el-form ref="ruleForm" :model="value" label-width="0px" class="demo-ruleForm" > -->
    <el-form-item>
      <el-table
          :data="value"
          :style="attrs.style"
          :show-header="attrs.is_title">
        <el-table-column
            v-if="attrs.show_index"
            type="index"
            :width="Number(attrs.index_width) + 20 + 'px'"
            :index="indexMethod">
        </el-table-column>
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
                v-if="show_delete"
                size="small">
              移除
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-form-item>
    <el-form-item v-if="show_add">
      <el-button style="display:block;margin: 0 auto;" type="text" size="middle" @click.native.prevent="addRow">添加
      </el-button>
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
				tableData: this.value,
				show_add: true,
				show_delete: false
			}
		},
		created() {
			this.columns = this.attrs.columns;
			console.log('this value');
			console.log(this.value);
			if(this.attrs.data){
				this.tableData = this.attrs.data.length <= 0 ? [{}] : this.attrs.data;
      }
			this.showAdd();
			this.showDelete();
			this.$emit("change", this.tableData)
		},
		watch: {
			value(value) {
				this.tableData = value;
			}
		},
		methods: {
			onChange(value) {
				this.$emit("change", this.tableData);
			},
			indexMethod(index) {
				if (this.attrs.index_model == 1) {
					return (this.attrs.index_prefix != null ? this.attrs.index_prefix : "") + (index + 1) + (this.attrs.index_after != null ? this.attrs.index_after : "");
				} else if (this.attrs.index_model == 2) {
					return (this.attrs.index_prefix != null ? this.attrs.index_prefix : "") + this.changeNumToHan(index + 1) + (this.attrs.index_after != null ? this.attrs.index_after : "");
				} else if (this.attrs.index_model == 3) {
          return (this.attrs.index_prefix != null ? this.attrs.index_prefix : "") + this.changeLetter(index) + (this.attrs.index_after != null ? this.attrs.index_after : "");
        }else {
					return (this.attrs.index_prefix != null ? this.attrs.index_prefix : "") + (index + 1) + (this.attrs.index_after != null ? this.attrs.index_after : "");
				}

			},
			changeNumToHan(num) {
				var arr1 = new Array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
				var arr2 = new Array('', '十', '百', '千', '万', '十', '百', '千', '亿', '十', '百', '千', '万', '十', '百', '千', '亿');//可继续追加更高位转换值
				if (!num || isNaN(num)) {
					return "零";
				}
				var english = num.toString().split("")
				var result = "";
				for (var i = 0; i < english.length; i++) {
					var des_i = english.length - 1 - i;//倒序排列设值
					result = arr2[i] + result;
					var arr1_index = english[des_i];
					result = arr1[arr1_index] + result;
				}
				//将【零千、零百】换成【零】 【十零】换成【十】
				result = result.replace(/零(千|百|十)/g, '零').replace(/十零/g, '十');
				//合并中间多个零为一个零
				result = result.replace(/零+/g, '零');
				//将【零亿】换成【亿】【零万】换成【万】
				result = result.replace(/零亿/g, '亿').replace(/零万/g, '万');
				//将【亿万】换成【亿】
				result = result.replace(/亿万/g, '亿');
				//移除末尾的零
				result = result.replace(/零+$/, '')
				//将【零一十】换成【零十】
				//result = result.replace(/零一十/g, '零十');//貌似正规读法是零一十
				//将【一十】换成【十】
				result = result.replace(/^一十/g, '十')
				return result;
			},
      changeLetter(num) {
				var arr = new Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        var result = arr[num];
				return result;
			},
			// 增加
			addRow() {
				let obj = {}
				this.tableData.push(obj);

				this.showAdd();
				this.showDelete();
			},
			//删除行
			deleteRow(index, rows) {
				if (this.tableData.length > 0) {
					this.tableData.splice(index, 1);
				}
				this.onChange();

				this.showAdd();
				this.showDelete();
			},
			showDelete() {
				if (this.tableData.length > this.attrs.min_num) {
					this.show_delete = true;
				} else {
					this.show_delete = false;
				}
			},
			showAdd() {
				if (this.tableData.length > this.attrs.max_num) {
					this.show_add = false;
				} else {
					this.show_add = true;
				}
			},
			// 调试代码信息用
			submitData() {
				console.log(this.tableData)
				console.log('this.attr_names', this.attr_names);
				console.log('this.tableData', this.tableData);
			}
		}
	};
</script>
<style lang="scss" scoped>
  .property-list {
    width: 80%;
  }
</style>
