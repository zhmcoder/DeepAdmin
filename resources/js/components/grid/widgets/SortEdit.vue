<template slot-scope="scope">
  <div class="rank">
    <input type="text" v-model="sort_value" @blur="sort_change(1)" :style="{ width: attrs.width + 'px'}"/>
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
				old_sort_value: this.value,
			};
		},
		mounted() {
		},
		methods: {
			onRequest(uri) {
				console.log(this.row)
				this.loading = true;
				this.$http
					.get(uri)
					.then(res => {
						if (res.code == 200) {
							if(this.attrs.showSummary){
								this.row[this.attrs.field] = this.sort_value;
              }

							console.log(this.row)
						}
					})
					.finally(() => {
						this.loading = false;
					});
			},
			sort_change(index) {
				console.log("index = " + index);
				console.log(this.sort_value + " sort value");
				console.log(this.old_sort_value + " old_sort_value");
				console.log(this.old_sort_value + " old_sort_value");
				if (this.attrs.action != null && this.old_sort_value != this.sort_value) {
					this.onRequest(this.attrs.action + '&id=' +
						this.row.id + "&sort_value=" + this.sort_value);
				}
			}
		}
	}
</script>
<style lang="scss" scoped>
  .rank {
    input {
      width: 100%;
      display: inline-block;
      border: 1px solid #DCDFE6;
      outline: none;
      padding: 5px;
      color: #606266;
    }
  }

</style>

