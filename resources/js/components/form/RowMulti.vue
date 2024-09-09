<template>
  <div class="property-list">
    <template v-for="(item, i) in attrs.component">
      <template>
        <span :style="item.labelStyle" :class="item.className">{{item.label}}</span>
      </template>
      <component
          v-model="multiData[item.prop]"
		  :key="i"
          v-if="item.component"
          :is="item.component.componentName"
          :attrs="item.component"
		  :multiData="multiData"
		  :itemprop="item.prop"
          @change="value=>onChange(value,item.component)"
      />
      <span :style="item.labelStyle" :class="item.className">
       {{item.afterLabel}}
      </span>
      <div v-if="item.wrap"></div>
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

			// console.log('multiItem');
			// console.log(this.multiData);
			// console.log(this.formData);
			// console.log(this.attrs);
			// console.log(this.attrs.component.prop);
			this.$emit("change", this.multiData)
		},
		watch: {
			value(value) {
				this.multiData = value;
			}
		},
		methods: {
			onChange(value,component) {
				this.$emit("change", this.multiData);

				// 如果是关联属性，修改attrs的值
				var newAttrs = JSON.parse(JSON.stringify(this.attrs))
				if(component.isRelatedSelect){
					this.$emit("changeMoreRelation", newAttrs , component ,value);

					// 循环遍历，将后面的关联数据重置
					this.resetFormData(component);
				}
			},
			// 使用递归重置关联数据
			resetFormData(component){
				if(component.isRelatedSelect){
					if(component.relatedSelectRef){
						this.multiData[component.relatedSelectRef] = null;
					}
					this.attrs.component.map(item=>{
						if(item.prop == component.relatedSelectRef){
							this.resetFormData(item.component)
						}
					})
				}
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
