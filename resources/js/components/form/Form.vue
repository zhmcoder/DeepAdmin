<template>
  <div class="form-page">
    <component
        v-if="attrs.top"
        :is="attrs.top.componentName"
        :attrs="attrs.top"
    />
    <component
        :is="attrs.attrs.isDialog ? 'div' : 'el-card'"
        shadow="never"
        class="form-card"
        v-loading="loading"
    >
      <el-form
          v-if="formData"
          :ref="attrs.ref || 'form'"
          :model="formData"
          :class="attrs.attrs.className"
          :style="attrs.attrs.style"
          :rules="attrs.formRules"
          :inline="attrs.attrs.inline"
          :label-position="attrs.attrs.labelPosition"
          :label-width="attrs.attrs.labelWidth"
          :label-suffix="attrs.attrs.labelSuffix"
          :hide-required-asterisk="attrs.attrs.hideRequiredAsterisk"
          :show-message="attrs.attrs.showMessage"
          :inline-message="attrs.attrs.inlineMessage"
          :status-icon="attrs.attrs.statusIcon"
          :validate-on-rule-change="attrs.attrs.validateOnRuleChange"
          :size="attrs.attrs.size"
          :disabled="attrs.attrs.disabled"
      >
        <component :is="attrs.attrs.hideTab ? 'div' : 'el-tabs'"
                   :tab-position="attrs.attrs.hideTab ? null : attrs.attrs.tabPosition "
                   v-model="activeName"
                   @tab-click="handleClick">
          <component
              :is="attrs.attrs.hideTab ? 'div' : 'el-tab-pane'"
              :label="tab"
              v-for="tab in attrs.tabs"
              :key="tab"
              :name="tab"
          >
            <template v-if="attrs.attrs.hideTab ? true : activeName == tab">
              <template v-for="(item, index) in attrs.formItems">
                <ItemIf
                    v-if="tab == item.tab"
                    :key="index"
                    :form_item="item"
                    :form_items="attrs.formItems"
                    :form_data="formData"
                >
                  <component
                      v-if="item.topComponent"
                      :is="item.topComponent.componentName"
                      :attrs="item.topComponent"
                  />

                  <el-form-item
                      :prop="item.prop"
                      :label-width="item.labelWidth"
                      :error="item.error"
                      :show-message="item.showMessage"
                      :inline-message="item.inlineMessage"
                      :size="item.size"
                  >
                    <span slot="label" v-if="!item.hideLabel">
                      {{ item.label }}
                    </span>
                    <template v-if="!item.component||(item.component&&item.component.type!='hidden')">
                      <el-col :span="item.inputWidth">
                        <template v-if="item.relationName">
                          <ItemDiaplsy
                              v-model="
                              formData[item.relationName][item.relationValueKey]
                            "
                              :default-prop-values="defaultFormData ? defaultFormData[item.relationName][item.relationValueKey]:null"
                              :form-item="item"
                              :form-items="attrs.formItems"
                              :form-data="formData"
                          />
                        </template>
                        <template v-else>
                          <ItemDiaplsy
                              v-model="formData[item.prop]"
                              :default-prop-values="defaultFormData ? defaultFormData[item.prop]:null"
                              :form-item="item"
                              :form-items="attrs.formItems"
                              :form-data="formData"
                          />
                        </template>

                        <div
                            v-if="item.help"
                            class="form-item-help"
                            v-html="item.help"
                        ></div>
                      </el-col>
                    </template>
                  </el-form-item>
                  <component
                      v-if="item.footerComponent"
                      :is="item.footerComponent.componentName"
                      :attrs="item.footerComponent"
                  />
                </ItemIf>
              </template>
            </template>
          </component>
        </component>
        <component
            :is="attrs.actions.fixed ? 'Affix' : 'div'"
            :offset-bottom="20"
        >
          <div
              class="form-bottom-actions flex padding-tb"
              :class="{ 'form-bottom-actions-fixedxxx': attrs.actions.fixed , 'form-bottom-actions_center':attrs.actions.buttonCenter}"
          >
            <div>
              <component
                  v-for="(component, index) in attrs.actions.addLeftActions"
                  :key="component.componentName + index"
                  :is="component.componentName"
                  :attrs="component"
              />
            </div>
            <div class="flex custom_style" :class="{'custom_style_center':attrs.actions.buttonCenter}">
              <component
                  v-for="(component, index) in attrs.actions.addRightActions"
                  :key="component.componentName + index"
                  :is="component.componentName"
                  :attrs="component"
              />
              <el-button
                v-for="(item, index) in attrs.actions.actionList" :key="index"
                :style="item.style"
                :class="item.className"
                :type="item.type"
                :size="item.size"
                :plain="item.plain"
                :round="item.round"
                :circle="item.circle"
                :disabled="item.disabled"
                :icon="item.icon"
                :autofocus="item.autofocus"
                :loading="loading"
                @click="submitFormActionlist((attrs.ref || 'form'), item)"
              >
                <template v-if="item.content">
                  {{ item.content }}
                </template>
              </el-button>

              <el-button
                  v-if="attrs.actions.cancelButton"
                  :style="attrs.actions.cancelButton.style"
                  :class="attrs.actions.cancelButton.className"
                  :type="attrs.actions.cancelButton.type"
                  :size="attrs.actions.cancelButton.size"
                  :plain="attrs.actions.cancelButton.plain"
                  :round="attrs.actions.cancelButton.round"
                  :circle="attrs.actions.cancelButton.circle"
                  :disabled="attrs.actions.cancelButton.disabled"
                  :icon="attrs.actions.cancelButton.icon"
                  :autofocus="attrs.actions.cancelButton.autofocus"
                  :loading="loading"
                  @click="onCancel"
              >
                <template v-if="attrs.actions.cancelButton.content">{{
                  attrs.actions.cancelButton.content
                  }}
                </template>
              </el-button>

              <el-button
                  v-if="attrs.actions.submitButton"
                  :style="attrs.actions.submitButton.style"
                  :class="attrs.actions.submitButton.className"
                  :type="attrs.actions.submitButton.type"
                  :size="attrs.actions.submitButton.size"
                  :plain="attrs.actions.submitButton.plain"
                  :round="attrs.actions.submitButton.round"
                  :circle="attrs.actions.submitButton.circle"
                  :disabled="attrs.actions.submitButton.disabled"
                  :icon="attrs.actions.submitButton.icon"
                  :autofocus="attrs.actions.submitButton.autofocus"
                  :loading="loading"
                  @click="submitForm(attrs.ref || 'form')"
              >
                <template v-if="attrs.actions.submitButton.content">{{
                  attrs.actions.submitButton.content
                  }}
                </template>
              </el-button>
            </div>
          </div>
        </component>
      </el-form>
    </component>
    <component
        v-if="attrs.bottom"
        :is="attrs.bottom.componentName"
        :attrs="attrs.bottom"
    />
  </div>
</template>
<script>
	import {BaseComponent} from "@/mixins.js";
	import ItemDiaplsy from "./ItemDiaplsy";
	import ItemIf from "./ItemIf";
	import {isNull} from "../../utils";
	import Affix from "../widgets/common/affix";

	export default {
		mixins: [BaseComponent],
		components: {
			ItemDiaplsy,
			ItemIf,
			Affix,
		},
		props: {
			attrs: Object,
            keys: String,
		},
		computed: {
			isEdit() {
				return this.attrs.mode == "edit";
			},
			ignoreKey() {
				return this._.map(
					this.attrs.formItems.filter(
						(e) => !e.ignoreEmpty || !isNull(this.formData[e.prop])
					),
					"prop"
				);
			},
            actionUrl() {
                const keys = this.$store.getters.thisPage.grids.selectionKeys;

                return this._.replace(this.attrs.action, "selectionKeys", keys);
            },
		},
		data() {
			return {
				loading: false,
				init: false,
				formData: null,
        defaultFormData:null,
        activeName:null,
			};
		},
    created(){
      if (this.attrs.target_url) {
        window.open(this.attrs.target_url, '_blank');
        if (this.attrs.target_url_2) {
          window.open(this.attrs.target_url_2, '_blank');
        }
      } else {
        this.activeName = this.attrs.tabValue;
      }
    },
		mounted() {
      // 存在dataUrl的时候获取后台数据
      this.formData = this._.cloneDeep(this.attrs.defaultValues);
      this.isEdit && this.getEditData();
      this.attrs.dataUrl && this.attrs.isGetData && this.getFormData();

      this.$bus.on("resetFormData", () => {
        this.formData = this._.cloneDeep(this.attrs.defaultValues);
      });
      this.$bus.on('setDefaultFormData',(data)=>{
        this.defaultFormData = data;
      })
		},
		destroyed() {
			this.formData = this._.cloneDeep(this.attrs.defaultValues);
			//取消监听
			try {
				this.$bus.off("resetFormData");
			} catch (e) {
			}
		},
		methods: {
      handleClick(tab, event){
          this.activeName = tab.name;
      },
      getFormData(){
        this.$http
					.get(this.attrs.dataUrl)
					.then(({data}) => {
						let temp_data = JSON.parse(JSON.stringify(data));
						//处理远程接口下拉
						this.attrs['formItems'].forEach(item => {
							if (item['component'] && item['component']['isRelatedSelect']) {
								let select_related = item['component']['relatedSelectRef'];
								let select_related_value = data[select_related];
								data[select_related] = null;
								this.attrs['formItems'].forEach(select_item => {
									if (select_item['prop'] == select_related) {
										if (select_item['component'] && select_item['component']['remoteUrl']) {
											this.$http
												.get(select_item['component']['remoteUrl'], {
													params: {
														[item.prop]: temp_data[item.prop]
													}
												})
												.then(res => {
													const select_data = res.data.data || res.data;
													if (select_data.length) {
														let length = select_item['component']['options'].length;
														for (let i = 0; i < length; i++) {
															select_item['component']['options'].splice(0, 1);
														}
													}
													select_item['component']['options'].push(...select_data);
                          if(!isNaN(select_related_value)){
													  data[select_related] = select_related_value * 1;
                          }else{
													  data[select_related] = select_related_value;
                          }
												});
										}

									}
								})
							}
						})
						this.formData = data;
						this.init = true;

						//发送表单编辑数据加载完毕事件
						this.$nextTick(() => {
							this.$bus.emit("EditDataLoadingCompleted");
						});
					})
					.finally(() => {
						this.loading = false;
					});
      },
			getEditData() {
				this.loading = true;
				this.init = false;
				this.$http
					.get(this.attrs.dataUrl, {
						params: {
							get_data: true,
						},
					})
					.then(({data}) => {
						let temp_data = JSON.parse(JSON.stringify(data));

            // 后台获取联动数据后，处理数据
            this.attrs['formItems'].forEach(item => {
              if(item['component'] && item['component']['componentName']=='RowMulti'){
                if(item.prop.indexOf('.') > -1) {
                  var propArr = item.prop.split('.');
                  var propsItem = data;
                  propArr.map(item => {
                    propsItem = propsItem[item]
                  })
                  this.getNewFormData(item['component'], propsItem, item.prop)
                } else {
                  this.getNewFormData(item['component'],data[item.prop],item.prop)
                }
              }
            })

						//处理远程接口下拉
						console.log('attrs');
						console.log(this.attrs);
						this.attrs['formItems'].forEach(item => {
							if (item['component'] && item['component']['isRelatedSelect']) {
								let select_related = item['component']['relatedSelectRef'];
								let select_related_value = data[select_related];
								data[select_related] = null;
								this.attrs['formItems'].forEach(select_item => {
									if (select_item['prop'] == select_related) {
										if (select_item['component'] && select_item['component']['remoteUrl']) {
											this.$http
												.get(select_item['component']['remoteUrl'], {
													params: {
														[item.prop]: temp_data[item.prop]
													}
												})
												.then(res => {
													const select_data = res.data.data || res.data;
													if (select_data.length) {
														let length = select_item['component']['options'].length;
														for (let i = 0; i < length; i++) {
															select_item['component']['options'].splice(0, 1);
														}
													}
													select_item['component']['options'].push(...select_data);
                          if(!isNaN(select_related_value)){
													  data[select_related] = select_related_value * 1;
                          }else{
													  data[select_related] = select_related_value;
                          }
												});
										}

									}
								})
							}
						})
						this.formData = data;
						this.init = true;

						//发送表单编辑数据加载完毕事件
						this.$nextTick(() => {
							this.$bus.emit("EditDataLoadingCompleted");
						});
					})
					.finally(() => {
						this.loading = false;
					});
			},
      // 递归查询编辑里面的数据
			getNewFormData(attrs,data,prop) {
        if(attrs.multiData){
          attrs.multiData = data;
        }
				var newAttrs = JSON.parse(JSON.stringify(attrs));
        this.deepFormData(newAttrs,data,prop,newAttrs.component,0);
      },
      deepFormData(newAttrs , data , prop ,  arr , i) {
        var _this = this;
        var attrsList = JSON.parse(JSON.stringify(newAttrs));
        var newFormItems = JSON.parse(JSON.stringify(this.attrs.formItems));
        if(arr[i].component.isRelatedSelect && arr[i].component.relatedSelectRef){
          attrsList.component.map(item=>{
            if(item.prop == arr[i].component.relatedSelectRef){
              this.$http.get(item.component.remoteUrl, {
                params: {
                  [arr[i].component.ref]: data[arr[i].prop]
                }
              })
              .then(res => {
                item.component.options = res.data.data;
                newFormItems.map(citem=>{
                  if(citem.prop == prop){
                    citem['component'] = attrsList
                  }
                })
                _this.attrs.formItems = newFormItems;
                this.deepFormData(attrsList , data , prop , arr , i+1);
              })
            }
          })
        }
      },
			submitForm(formName) {
				this.$refs[formName].validate((valid) => {
					if (valid) {
						this.loading = true;
						const formatData = this._.pick(this.formData, this.ignoreKey);
						if (this.isEdit) {
							this.$http
								.put(this.actionUrl, formatData)
								.then(({data, code, message}) => {
									if (code == 200) {
										if (this.attrs.attrs.isDialog) {
                      if(data && data.action && data.action.emit == 'window') {
                        this.closeDialog();
                        window.location.href = data.action.target_url;
                      }else{
                        this.closeDialog();
                        this.$bus.emit("tableReload");
                        this.$bus.emit("reloadGridFrom");
                      }

										} else {
											this.successRefData();
										}
									}
								})
								.finally(() => {
									this.loading = false;
								});
						} else {
							this.$http
								.post(this.actionUrl, formatData)
								.then(({data, code, message}) => {
									if (code == 200) {
										if (this.attrs.attrs.isDialog) {
                      if(data && data.action && data.action.emit == 'window') {
                        this.closeDialog();
                        window.location.href = data.action.target_url;
                      }else{
                        this.closeDialog();
                        this.$bus.emit("tableReload");
                        this.$bus.emit("reloadGridFrom");
                      }
										} else {
											this.successRefData();
										}
									}
								})
								.finally(() => {
									this.loading = false;
								});
						}
					} else {
						return false;
					}
				});
			},
      submitFormActionlist(formName, action) {
				this.$refs[formName].validate((valid) => {
					if (valid) {
						this.loading = true;
						const formatData = this._.pick(this.formData, this.ignoreKey);
						if (this.isEdit) {
							this.$http
								.put(action.resource, formatData)
								.then(({data, code, message}) => {
									if (code == 200) {
                    if(data && data.action && data.action.emit == 'window') {
                      if(this.attrs.attrs.isDialog){
                        this.closeDialog();
                      }
                      window.location.href = data.action.target_url;
                    } else {
                      if (this.attrs.attrs.isDialog) {
                        this.closeDialog();
                        this.$bus.emit("tableReload");
                        this.$bus.emit("reloadGridFrom");
                      } else {
                        this.successRefData();
                      }
                    }
									}
								})
								.finally(() => {
									this.loading = false;
								});
						} else {
              console.log("action.reource", action.resource)
							this.$http
								.post(action.resource, formatData)
								.then(({data, code, message}) => {
									if (code == 200) {
                    if(data && data.action && data.action.emit == 'window') {
                      window.location.href = data.action.target_url
                    } else {
                      if (this.attrs.attrs.isDialog) {
                        this.closeDialog();
                        this.$bus.emit("tableReload");
                        this.$bus.emit("reloadGridFrom");
                      } else {
                        this.successRefData();
                      }
                    }
									}
								})
								.finally(() => {
									this.loading = false;
								});
						}
					} else {
						return false;
					}
				});
			},
			successRefData() {
				if (this.attrs.formRefData.successRefData) {
					this.$bus.emit(this.attrs.formRefData.successRefData.ref, {
						data: this.attrs.formRefData.successRefData.data,
						self: this,
					});
				} else {
					this.$router.go(-1);
				}
			},
			resetForm(formName) {
				this.$refs[formName].resetFields();
			},
			onCancel() {
				if (this.attrs.actions.cancelButton.afterEmit && this.attrs.actions.cancelButton.afterEmit.length > 0) {
					this.attrs.actions.cancelButton.afterEmit.forEach(item => {
						this.$bus.emit(item.eventName, item.eventData);
					})
					this.closeDialog();
				} else {
					this.attrs.attrs.isDialog ? this.closeDialog() : this.$router.go(-1);
				}

			},
			closeDialog() {
        if(this.attrs.isGetData){
				  this.$bus.emit("closeDialog");
        }else{
          if(this.attrs.actions.cancelButton.isDialog){
				    this.$bus.emit("closeDialog");
          }else{
				    this.$bus.emit("showDialogGridFrom", {isShow: false});
          }
        }
			},
		},
	};
</script>
<style lang="scss">
  .form-page {
    .form-card {
      min-height: 100px;
    }

    .form-bottom-actions {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .form-bottom-actions_center {
      justify-content: center;
    }

    //deep admin start
    .custom_style {
      padding-right: 100px;
    }

    .custom_style_center {
      padding-right: 0px;
    }

    //deep admin end
    .admin-affix {
      .form-bottom-actions {
        padding: 6px;
        background: #ffffff;
        border-radius: 4px;
        border: 1px solid #ebeef5;
      }
    }

    .form-item-help {
      color: #999;
    }
  }
</style>
