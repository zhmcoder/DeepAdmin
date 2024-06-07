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
      }
    },
    updated(){
      console.log('updated')
    },
    mounted() {
      this.randomEditorId = 'ueditor_' + parseInt(Math.random() * 1000)
      this.loadCDNJS(`${this.attrs.jsBasePath}ueditor.config.js`)
      this.loadCDNJS(`${this.attrs.jsBasePath}ueditor.all.min.js`, true).then(_=>{
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
        if(UE.getEditor) {
          this.ueditor = UE.getEditor(this.randomEditorId, {
            UEDITOR_HOME_URL: this.attrs.jsBasePath,
            initialContent: this.value || this.attrs.componentValue || '',
            // toolbars: [this.attrs.menus],
            zIndex: this.attrs.zIndex,
            readonly: this.attrs.disabled
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