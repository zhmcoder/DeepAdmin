(window.webpackJsonp=window.webpackJsonp||[]).push([[5],{O0RY:function(t,e,i){(t.exports=i("I1BE")(!1)).push([t.i,"\n.edui-default .edui-toolbar {\r\n    line-height: initial;\n}\n.edui-default .edui-toolbar .edui-combox .edui-combox-body {\r\n    line-height: initial;\n}\r\n",""])},eZmF:function(t,e,i){"use strict";i.r(e);var o=i("b3nO"),n=(i("vDqi"),{components:{},mixins:[o.b],props:["defaultPropValues","attrs"],data:function(){return{randomEditorId:"ueditor",defaultValue:"",editorHtml:"111",ueditor:null,editorHomeUrl:null}},updated:function(){console.log("updated")},mounted:function(){var t=this;this.editorHomeUrl=this.attrs.jsBasePath?this.attrs.jsBasePath:window.location.origin+"/UEditor/",this.randomEditorId="ueditor_"+parseInt(1e3*Math.random()),this.loadCDNJS("".concat(this.editorHomeUrl,"ueditor.config.js")),this.loadCDNJS("".concat(this.editorHomeUrl,"ueditor.all.min.js"),!0).then((function(e){t.initEditor()})),this.$nextTick((function(t){}))},watch:{value:function(t){t!==this.ueditor.getContent()&&t&&this.ueditor.setContent(t)}},unmounted:function(){this.ueditor&&this.ueditor.destroy()},methods:{initEditor:function(){var t=this;UE.getEditor&&(this.ueditor=UE.getEditor(this.randomEditorId,{UEDITOR_HOME_URL:this.editorHomeUrl,initialContent:this.value||this.attrs.componentValue||"",zIndex:this.attrs.zIndex,readonly:this.attrs.disabled}),this.ueditor.ready((function(){t.ueditor.setContent(t.value||""),t.ueditor.addListener("contentchange",(function(){t.onChange(t.ueditor.getContent())}))})))},loadCDNJS:function(t,e){return new Promise((function(i,o){var n=document.createElement("script");n.type="text/javascript",n.src=t,e&&(n.defer="defer"),n.readyState?n.onreadystatechange=function(){"loaded"!=n.readyState&&"complete"!=n.readyState||(n.onreadystatechange=null,i())}:n.onload=function(){console.log("load",t,"success"),i()},document.getElementsByTagName("head")[0].appendChild(n)}))}}}),r=(i("sfaA"),i("KHd+")),a=Object(r.a)(n,(function(){var t=this._self._c;return t("div",{staticClass:"wangeditor-main flex-sub"},[this.attrs.component?t("div",[t(this.attrs.component.componentName,{tag:"component",attrs:{attrs:this.attrs.component,editor:this.editor}})],1):this._e(),this._v(" "),t("div",{staticStyle:{width:"100%"},attrs:{id:this.randomEditorId}})])}),[],!1,null,null,null);e.default=a.exports},"j0/C":function(t,e,i){var o=i("O0RY");"string"==typeof o&&(o=[[t.i,o,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};i("aET+")(o,n);o.locals&&(t.exports=o.locals)},sfaA:function(t,e,i){"use strict";i("j0/C")}}]);