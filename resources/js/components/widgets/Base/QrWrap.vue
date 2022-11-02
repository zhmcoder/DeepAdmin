<template>
  <div class="qr_wrap">
    <div class="title">答卷地址</div>
    <div class="desc">复制下面的问卷链接到微信工具中打开</div>
    <div class="qr_top">
      <div class="href_wrap">
        <div class="href">{{attrs.href}}</div>
        <div class="copy_btn" @click="copyText(attrs.href)">复制链接</div>
      </div>
      <a class="open_btn" :href="attrs.href" target="_blank">打开</a>
    </div>
    <div class="title">二维码手机答题</div>
    <div class="qr_bottom">
      <div class="qr_img">
        <el-image
          title="预览图片"
          :src="attrs.src"
          fit="cover"
          class="upload-show-image"
          :preview-src-list="[attrs.src]"
        />
      </div>
      <!-- <div class="download_btn" @click="download">下载二维码</div> -->
      <a class="download_btn" :href="attrs.down" target="_blank">下载二维码</a>
    </div>
  </div>
</template>
<script>
import { BaseComponent } from "@/mixins.js";
export default {
  mixins: [BaseComponent],
  props: {
    attrs: Object,
  },
  data() {
    return {
      // attrs: {
      //   href: 'https://pka.image.zdapk.cn/image-raw/39630_raw.jpg',
      //   src: 'https://pka.image.zdapk.cn/image-raw/39630_raw.jpg'
      // },
    };
  },
  methods: {
    open_href() {
      window.location.href = this.attrs.href
    },
    copyText(copytext) {
      const text = document.createElement('textarea'); // 创建节点
      text.setAttribute('readonly', 'readonly');
      text.value = copytext; // 赋值
      document.body.appendChild(text); // 插入节点
      text.setSelectionRange(0, text.value.length);
      text.select(); // 选中节点
      document.execCommand('copy'); // 执行浏览器复制方法
      if (document.body.removeChild(text)) {
          this.$message({ type: 'success', message: '复制成功' })
      } else {
          this.$message({ type: 'error', message: '复制失败' })
      }
    },
    getUrlBase64(url) {
      console.log("url", url)
      try {
        return new Promise(resolve => {
          let canvas = document.createElement('canvas')
          let ctx = canvas.getContext('2d')
          let img = new Image()
          img.crossOrigin = 'Anonymous' //允许跨域
          img.src = url
          img.onload = function() {
            canvas.height = 300
            canvas.width = 300
            ctx.drawImage(img, 0, 0, 300, 300)
            let dataURL = canvas.toDataURL('image/png')
            canvas = null
            console.log("dataURL", dataURL)
            resolve(dataURL)
          }
        })
      } catch (error) {
        console.log("error", error)
      }
      
    },
    download() {
      console.log("下载")
      try {
        this.getUrlBase64(this.attrs.src).then(base64 => {
          console.log("base64", base64)
          let link = document.createElement('a')
          link.href = base64
          link.download = 'qrCode.png'
          link.click()
        })
        
      } catch (error) {
        conssole.log("error====", error)
      }
      return
      var imgsrc = this.attrs.src;
      var name = "二维码";
      //下载图片地址和图片名
      var image = new Image();
      // 解决跨域 Canvas 污染问题
      image.setAttribute("crossOrigin", "anonymous");
      image.onload = function() {
          var canvas = document.createElement("canvas");
          canvas.width = image.width;
          canvas.height = image.height;
          var context = canvas.getContext("2d");
          context.drawImage(image, 0, 0, image.width, image.height);
          var url = canvas.toDataURL("image/png"); //得到图片的base64编码数据'
          var a = document.createElement("a"); // 生成一个a元素
          var event = new MouseEvent("click", {
              bubbles: true,
              cancelable: true,
              view: window
          }); // 创建一个单击事件
          a.download = name || "photo"; // 设置图片名称
          a.href = url; // 将生成的URL设置为a.href属性
          a.dispatchEvent(event); // 触发a的单击事件
      };
      image.src = imgsrc;
    }
  }
  
}
</script>
<style>
  .qr_wrap {
    margin: 10px;
  }
  .title {
    font-size: 16px;
    margin-top: 5px;
  }
  .desc {
    font-size: 14px;
    color: #A3A2A2;
  }
  .qr_top {
    /* width: 80%; */
    margin-top: 10px;
    margin-bottom: 20px;
  }
  .href_wrap {
    min-height: 30px;
    display: flex;
    justify-content: space-between;
    border: 1px solid #D2D2D2;
    position: relative;
    border-radius: 4px;
  }
  .href {
    padding: 4px;
    font-size: 16px;
    display: flex;
    align-items: center;
  }
  .copy_btn {
    width: 80px;
    height: 100%;
    text-align: center;
    background: #5F96C0;
    color: white;
    margin-left: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    right: 0px;
    top: 0px;
    bottom: 0px;
    cursor: pointer;
  }
  .open_btn {
    display: block;
    width: 80px;
    height: 35x;
    line-height: 35px;
    border-radius: 4px;
    text-align: center;
    background: #859095;
    color: white;
    margin-top: 10px;  
    cursor: pointer;  
  }
  .qr_bottom {
    margin-top: 10px;
    display: flex;
  }
  .qr_img {
    width: 220px;
    height: 200px;
  }
  .download_btn {
    width: 100px;
    height: 35px;
    line-height: 35px;
    border-radius: 4px;
    text-align: center;
    background: #5F96C0;
    color: white;
    margin-left: 15px;
    cursor: pointer;
  }
</style>