(window.webpackJsonp=window.webpackJsonp||[]).push([[4],{BdhI:function(t,e,o){"use strict";o.r(e);var n=o("GguQ"),i=o.n(n),a={mixins:[o("b3nO").b],props:["defaultPropValues"],data:function(){return{editor:null,initHtml:!1,defaultValue:""}},mounted:function(){var t=this;this.defaultValue=this._.cloneDeep(this.attrs.componentValue),this.editor=new i.a(this.$refs.toolbar,this.$refs.editor),this.editor.customConfig.menus=this.attrs.menus,this.editor.customConfig.zIndex=this.attrs.zIndex,this.editor.customConfig.uploadImgShowBase64=this.attrs.uploadImgShowBase64,this.attrs.uploadImgServer&&(this.editor.customConfig.uploadImgServer=this.attrs.uploadImgServer,this.editor.customConfig.uploadImgParams={_token:Admin.token}),this.attrs.uploadFileName&&(this.editor.customConfig.uploadFileName=this.attrs.uploadFileName),this.attrs.uploadImgHeaders&&(this.editor.customConfig.uploadImgHeaders=this.attrs.uploadImgHeaders),this.editor.customConfig.onchange=function(e){t.onChange(e)},this.$nextTick((function(){t.editor.create(),t.editor.txt.html(t.defaultValue),console.log("获取value====",t.value),t.value&&t.editor&&t.editor.txt.html(t.value)})),this.$bus.on("EditDataLoadingCompleted",(function(){t.editor&&t.editor.txt.html(t.value)}))},watch:{defaultPropValues:function(t){t!==this.editor.txt.html()&&t&&this.editor.txt.html(JSON.parse(JSON.stringify(String(t))))}},destroyed:function(){try{this.$bus.off("EditDataLoadingCompleted")}catch(t){}}},r=(o("CxPf"),o("KHd+")),s=Object(r.a)(a,(function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"wangeditor-main flex-sub"},[o("div",{ref:"toolbar",staticClass:"toolbar"}),t._v(" "),t.attrs.component?o("div",[o(t.attrs.component.componentName,{tag:"component",attrs:{attrs:t.attrs.component,editor:t.editor},on:{"update:editor":function(e){t.editor=e}}})],1):t._e(),t._v(" "),o("div",{ref:"editor",class:t.attrs.className,style:t.attrs.style})])}),[],!1,null,null,null);e.default=s.exports},CxPf:function(t,e,o){"use strict";o("sH8k")},ZOpH:function(t,e,o){(t.exports=o("I1BE")(!1)).push([t.i,".wangeditor-main {\n  border: 1px solid #dcdcdc;\n}\n.wangeditor-main .toolbar {\n  background: #f7f7f7;\n}\n.wangeditor-main .w-e-text-container {\n  position: relative;\n}\n.wangeditor-main .w-e-text-container .w-e-text {\n  position: absolute;\n  left: 0px;\n  top: 0px;\n  right: 0px;\n  bottom: 0px;\n}\n.wangeditor-main .w-e-text-container {\n  z-index: 1 !important;\n}\n.wangeditor-main .w-e-toolbar {\n  flex-wrap: wrap;\n}\n.wangeditor-main .w-e-menu {\n  z-index: auto !important;\n}\n.wangeditor-main .w-e-menu .w-e-droplist {\n  z-index: 2 !important;\n}",""])},sH8k:function(t,e,o){var n=o("ZOpH");"string"==typeof n&&(n=[[t.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};o("aET+")(n,i);n.locals&&(t.exports=n.locals)}}]);