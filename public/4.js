(window.webpackJsonp=window.webpackJsonp||[]).push([[4],{BdhI:function(t,e,o){"use strict";o.r(e);var n=o("b60y"),i=o.n(n),a={mixins:[o("b3nO").b],props:["defaultPropValues"],data:function(){return{editor:null,initHtml:!1,defaultValue:""}},mounted:function(){var t=this,e=this;this.defaultValue=this._.cloneDeep(this.attrs.componentValue),this.editor=new i.a(this.$refs.toolbar,this.$refs.editor),this.editor.config.menus=this.attrs.menus,console.log("this.attrs.zIndex",this.attrs.zIndex,this.value),this.editor.config.zIndex=this.attrs.zIndex,this.editor.config.uploadImgShowBase64=this.attrs.uploadImgShowBase64,this.attrs.uploadImgServer&&(console.log("this.attrs.uploadImgServer",this.attrs.uploadImgServer),this.editor.config.uploadImgServer=this.attrs.uploadImgServer,this.editor.config.uploadImgParams={_token:Admin.token}),this.attrs.uploadFileName&&(this.editor.config.uploadFileName=this.attrs.uploadFileName),this.attrs.uploadImgHeaders&&(this.editor.config.uploadImgHeaders=this.attrs.uploadImgHeaders),this.editor.config.onchange=function(e){t.onChange(e)},this.editor.config.customUploadImg=function(t,o){var n=new FormData;for(var i in t)n.append("file"+i,t[i]);n.append("amount",t.length),n.append("_token",Admin.token),e.$http.post(e.attrs.uploadImgServer,n).then((function(t){for(var e=0;e<=t.data.length-1;e++)o(t.data[e].url)}))},this.$nextTick((function(){t.editor.create(),t.editor.txt.html(t.defaultValue),console.log("获取value====",t.value),t.value&&t.editor&&t.editor.txt.html(t.value);var e=document.getElementById("w-e-text-container").clientHeight,o=document.getElementsByClassName("w-e-text");console.log("w_e_text[0]",o[0]),o&&o.length&&(o[0].style.minHeight=e+"px")})),this.$bus.on("EditDataLoadingCompleted",(function(){t.editor&&t.editor.txt.html(t.value)}))},watch:{defaultPropValues:function(t){t!==this.editor.txt.html()&&t&&this.editor.txt.html(JSON.parse(JSON.stringify(String(t))))}},destroyed:function(){try{this.$bus.off("EditDataLoadingCompleted")}catch(t){}},methods:{insertImgFn:function(){console.log("插入图片")}}},r=(o("YB7C"),o("KHd+")),s=Object(r.a)(a,(function(){var t=this,e=t._self._c;return e("div",{staticClass:"wangeditor-main flex-sub"},[e("div",{ref:"toolbar",staticClass:"toolbar"}),t._v(" "),t.attrs.component?e("div",[e(t.attrs.component.componentName,{tag:"component",attrs:{attrs:t.attrs.component,editor:t.editor},on:{"update:editor":function(e){t.editor=e}}})],1):t._e(),t._v(" "),e("div",{ref:"editor",class:t.attrs.className,style:t.attrs.style,attrs:{id:"w-e-text-container"}})])}),[],!1,null,null,null);e.default=s.exports},PYhp:function(t,e,o){var n=o("kM1C");"string"==typeof n&&(n=[[t.i,n,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};o("aET+")(n,i);n.locals&&(t.exports=n.locals)},YB7C:function(t,e,o){"use strict";o("PYhp")},kM1C:function(t,e,o){(t.exports=o("I1BE")(!1)).push([t.i,".wangeditor-main {\n  border: 1px solid #dcdcdc;\n}\n.wangeditor-main .toolbar {\n  background: #f7f7f7;\n}\n.wangeditor-main .w-e-toolbar {\n  flex-wrap: wrap;\n}\n.wangeditor-main .w-e-menu {\n  z-index: auto !important;\n}\n.wangeditor-main .w-e-menu .w-e-droplist {\n  z-index: 2 !important;\n}",""])}}]);