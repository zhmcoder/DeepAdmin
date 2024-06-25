<template>
  <div class="wangeditor-main flex-sub">
    <!-- <div ref="toolbar" class="toolbar"></div> -->
    <div v-if="attrs.component">
      <component
        :is="attrs.component.componentName"
        :attrs="attrs.component"
        :editor="editor"
      />
    </div>
    <div :id="randomEditorId" style="width: 100%;"></div>
  </div>
</template>
<script>

  import { FormItemComponent } from '@/mixins.js'
  import axios from 'axios'
  import Cookies from "js-cookie";

  export default {
    components: {
    },
    mixins: [FormItemComponent],
    props: ['defaultPropValues', 'attrs'],
    data() {
      return {
        randomEditorId: 'ueditor',
        defaultValue: '',
        editorHtml: '111',
        ueditor: null,
        editorHomeUrl: null,
      }
    },
    updated(){
    },
    mounted() {
      this.editorHomeUrl = this.attrs.jsBasePath ? this.attrs.jsBasePath : window.location.origin + '/UEditor/'
      // if (this.editorHomeUrl.includes('localhost')) {
      //   this.editorHomeUrl = 'https://plm.zdapk.cn/UEditor/'
      // }
      this.randomEditorId = 'ueditor_' + parseInt(Math.random() * 1000)
      this.loadCDNJS(`${this.editorHomeUrl}ueditor.config.js`)
      this.loadCDNJS(`${this.editorHomeUrl}ueditor.all.js?v=2`, true).then(_=>{
        this.initEditor()
      })
      this.$nextTick(_=> {
        
      })
    },
    watch: {
      value(value) {
        if (value !== this.ueditor.getContent() && value) {
          this.ueditor.setContent(value)
        }
      },
    },
    unmounted() {
      if(this.ueditor) this.ueditor.destroy()
    },
    methods: {
      initEditor() {
        const _this = this;
        console.log('###$1', Admin, this.attrs)
        if(UE.getEditor) {
          this.ueditor = UE.getEditor(this.randomEditorId, {
            UEDITOR_HOME_URL: this.editorHomeUrl,
            initialContent: this.value || this.attrs.componentValue || '',
            allHtmlEnabled: true,
            headers: {
              // 'Content-Type': 'multipart/form-data',
              _token: Admin.token,
              // 'xsrf-token': Cookies.get('XSRF-TOKEN')
            },
            // toolbars: [this.attrs.menus],
            zIndex: this.attrs.zIndex,
            serverUrl: window.location.origin + '/' + this.attrs.uploadImgServer,
            readonly: this.attrs.disabled,
            axios: axios,
          })
          this.ueditor.ready(function(){
            _this.ueditor.setContent(_this.value || '')
            _this.ueditor.addListener("contentchange", function () {
                _this.onChange(_this.ueditor.getContent());
            })
          })
        }
      },
      loadCDNJS(url, defer) {
        return new Promise((resolve, reject) => {
          const script = document.createElement('script');
          script.type = 'text/javascript';
          script.src = url;
          if (defer) script.defer = 'defer'

          if (script.readyState) {  // 仅限IE
            script.onreadystatechange = function() {
              if (script.readyState == "loaded" || script.readyState == "complete") {
                script.onreadystatechange = null;
                resolve();
              }
            };
          } else {  // 其他浏览器
            script.onload = function() {
              console.log('load', url, 'success')
              resolve();
            };
          }
          document.getElementsByTagName("head")[0].appendChild(script);
        })
      }
    },
  }
</script>
<style>
.edui-default .edui-toolbar {
    line-height: initial;
}

.edui-default .edui-toolbar .edui-combox .edui-combox-body {
    line-height: initial;
}
</style>