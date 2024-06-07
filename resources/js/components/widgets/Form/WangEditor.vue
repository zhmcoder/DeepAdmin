<template>
  <div class="wangeditor-main flex-sub">
    <div ref="toolbar" class="toolbar"></div>
    <div v-if="attrs.component">
      <component
        :is="attrs.component.componentName"
        :attrs="attrs.component"
        :editor.sync="editor"
      />
    </div>
    <div
      ref="editor"
      :style="attrs.style"
      id="w-e-text-container"
      :class="attrs.className"
    ></div>
  </div>
</template>
<script>
import E from "wangeditor";
import { FormItemComponent } from "@/mixins.js";
export default {
  mixins: [FormItemComponent],
  props: ["defaultPropValues"],
  data() {
    return {
      editor: null,
      initHtml: false,
      defaultValue: "",
    };
  },
  mounted() {
    var _this = this;
    this.defaultValue = this._.cloneDeep(this.attrs.componentValue);

    this.editor = new E(this.$refs.toolbar, this.$refs.editor);
    this.editor.i18next = window.i18next;
    this.editor.config.lang = 'en'
    // this.editor.customConfig.menus = this.attrs.menus;
    // this.editor.customConfig.zIndex = this.attrs.zIndex;
    // this.editor.customConfig.uploadImgShowBase64 = this.attrs.uploadImgShowBase64;
    // if (this.attrs.uploadImgServer) {
    //   this.editor.customConfig.uploadImgServer = this.attrs.uploadImgServer;

    //   this.editor.customConfig.uploadImgParams = {
    //     _token: Admin.token
    //   };
    // }
    // //自定义 fileName
    // if (this.attrs.uploadFileName) {
    //   this.editor.customConfig.uploadFileName = this.attrs.uploadFileName;
    // }
    // //自定义 header
    // if (this.attrs.uploadImgHeaders) {
    //   this.editor.customConfig.uploadImgHeaders = this.attrs.uploadImgHeaders;
    // }

    // this.editor.customConfig.onchange = html => {
    //   this.onChange(html);
    // };

    this.editor.config.menus = this.attrs.menus;
    console.log("this.attrs.zIndex", this.attrs.zIndex);
    this.editor.config.zIndex = this.attrs.zIndex;
    this.editor.config.uploadImgShowBase64 = this.attrs.uploadImgShowBase64;
    if (this.attrs.uploadImgServer) {
      console.log("this.attrs.uploadImgServer", this.attrs.uploadImgServer);
      this.editor.config.uploadImgServer = this.attrs.uploadImgServer;

      this.editor.config.uploadImgParams = {
        _token: Admin.token,
      };
    }
    //自定义 fileName
    if (this.attrs.uploadFileName) {
      this.editor.config.uploadFileName = this.attrs.uploadFileName;
    }
    //自定义 header
    if (this.attrs.uploadImgHeaders) {
      this.editor.config.uploadImgHeaders = this.attrs.uploadImgHeaders;
    }

    this.editor.config.onchange = (html) => {
      this.onChange(html);
    };
    this.editor.config.customUploadImg = function (resultFiles, insertImgFn) {
      // resultFiles 是 input 中选中的文件列表
      // insertImgFn 是获取图片 url 后，插入到编辑器的方法

      // 上传图片，返回结果，将图片插入到编辑器中
      let formdata = new FormData();
      for (let i in resultFiles) {
        formdata.append("file" + i, resultFiles[i]);
      }
      formdata.append("amount", resultFiles.length);
      formdata.append("_token", Admin.token);
      _this.$http.post(_this.attrs.uploadImgServer, formdata).then((data) => {
        for (let i = 0; i <= data.data.length - 1; i++) {
          insertImgFn(data.data[i].url);
        }
      });
    };

    this.$nextTick(() => {
      this.editor.create();
      this.editor.txt.html(this.defaultValue);
      console.log("获取value====", this.value);
      if (this.value) {
        this.editor && this.editor.txt.html(this.value);
      }
      var w_e = document.getElementById("w-e-text-container");
      var clientHeight = w_e.clientHeight;
      var w_e_text = document.getElementsByClassName("w-e-text");
      console.log("w_e_text[0]", w_e_text[0]);
      if (w_e_text && w_e_text.length) {
        w_e_text[0].style.minHeight = clientHeight + "px";
      }
    });
    //编辑数据加载完毕设置编辑器的值
    this.$bus.on("EditDataLoadingCompleted", () => {
      this.editor && this.editor.txt.html(this.value);
    });
  },
  watch: {
    defaultPropValues(value) {
      if (value !== this.editor.txt.html() && value) {
        this.editor.txt.html(JSON.parse(JSON.stringify(String(value))));
      }
    },
  },
  destroyed() {
    try {
      this.$bus.off("EditDataLoadingCompleted");
    } catch (e) {}
  },
  methods: {
    insertImgFn() {
      console.log("插入图片");
    },
  },
};
</script>
<style lang="scss">
// .wangeditor-main {
//   border: 1px solid #dcdcdc;
//   .toolbar {
//     background: #f7f7f7;
//   }
//   .w-e-text-container{
//     position: relative;
//     .w-e-text{
//       position: absolute;
//       left: 0px;
//       top: 0px;
//       right: 0px;
//       bottom: 0px;
//     }
//   }
//   .w-e-text-container {
//     // 文本框里面的层级调低
//     z-index: 1 !important;
//     background-color: var(--w-e-textarea-bg-color);
//     color: var(--w-e-textarea-color);
//     height: 100%;
//   }
//   .w-e-toolbar {
//     // 给工具栏换行
//     flex-wrap: wrap;
//   }
//   .w-e-menu {
//     // 最重要的一句代码
//     z-index: auto !important;

//     .w-e-droplist {
//       // 触发工具栏后的显示框调高
//       z-index: 2 !important;
//     }
// }
// }

.wangeditor-main {
  border: 1px solid #dcdcdc;
  .toolbar {
    background: #f7f7f7;
  }
  .w-e-toolbar {
    // 给工具栏换行
    flex-wrap: wrap;
  }
  .w-e-menu {
    // 最重要的一句代码
    z-index: auto !important;

    .w-e-droplist {
      // 触发工具栏后的显示框调高
      z-index: 2 !important;
    }
  }
}
</style>
