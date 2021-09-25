<template>
  <div class="property-list">
    <template v-for="(item, i) in attrs.component">
      <template>
        <span :style="item.labelStyle" :class="item.className">{{item.label}}</span>
      </template>
      <component
          v-model="multiData[item.prop]"
          v-if="item.component"
          :is="item.component.componentName"
          :attrs="item.component"
          @change="onChange"
      />
      <span :style="item.labelStyle" :class="item.className">
       {{item.afterLabel}}
     </span>
    </template>
  </div>
</template>
<script>
	export default {
		props: ["attrs", "value", "formData"],
		data() {
			return {
				columns: [],
				multiData: this.value
			}
		},
		created() {
			this.columns = this.attrs.columns;
			if(this.attrs.multiData){
				this.multiData = this.attrs.multiData.length <= 0 ? [{}] : this.attrs.multiData;
      }

			console.log('multiItem');
			console.log(this.multiData);
			console.log(this.formData);
			console.log(this.attrs);
			console.log(this.attrs.component.prop);
			this.$emit("change", this.multiData)
		},
		watch: {
			value(value) {
				this.multiData = value;
			}
		},
		methods: {
			onChange(value) {
				console.log('change');
				console.log(value);
				this.$emit("change", this.multiData);
			},
			// 增加
			addRow() {
				let obj = {}
				this.tableData.push(obj);
			},
			//删除行
			deleteRow(index, rows) {
				if (this.tableData.length > 1) {
					rows.splice(index, 1);
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
    width: 100%;
  }
</style>
