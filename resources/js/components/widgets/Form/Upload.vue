<template>
  <div>
    <div class="upload-component">
      <div class="upload-images">
        <draggable class="upload-images-draggable" tag="ul" v-model="list" v-bind="dragOptions" @update="datadragEnd" @start="drag = true" @end="drag = false" style="margin-:-40px;">
          <template v-for="(item, index) in list">
            <div :key="index" class="upload-images-item">
              <el-image
                title="预览图片"
                v-if="attrs.type == 'image'"
                :src="item.url"
                :style="{ width: attrs.width + 'px', height: attrs.height + 'px' }"
                fit="cover"
                class="upload-show-image"
                :preview-src-list="urlList"
              />
              <el-avatar
                v-if="attrs.type == 'file'"
                :size="attrs.width"
                shape="square"
                :title="item.name"
                fit="cover"
                icon="el-icon-document-checked"
              />
              <el-avatar v-else-if="attrs.type == 'avatar'" :size="attrs.width" :src="item.url" />
              <i @click="onDelete(index,item)" class="mask el-icon-close" title="删除图片"></i>
            </div>
          </template>
        </draggable>
      </div>
      <div
        class="upload-block"
        :class="{ 'ml-10': list.length > 0 }"
        v-if="(attrs.limit && list.length < attrs.limit) || !attrs.limit"
      >
        <el-upload
          ref="upload"
          :style="attrs.style"
          :class="attrs.className"
          :multiple="attrs.multiple"
          :data="data"
          :show-file-list="false"
          :drag="attrs.drag"
          :accept="attrs.accept"
          list-type="text"
          :disabled="attrs.disabled"
          :on-exceed="onExceed"
          :on-success="onSuccess"
          :on-remove="onRemove"
          action="#"
          :file-list="fileList"
          :http-request="handleRequest"
          v-if="attrs.showProgress"
          :limit="attrs.limit"
        >
          <el-button
            plain
            :class="attrs.type"
            :style="{ width: attrs.width + 'px', height: attrs.height + 'px' }"
          >上传</el-button>
        </el-upload>

        <el-upload
          ref="upload"
          :style="attrs.style"
          :class="attrs.className"
          :action="attrs.action"
          :multiple="attrs.multiple"
          :data="data"
          :show-file-list="false"
          :drag="attrs.drag"
          :accept="attrs.accept"
          list-type="text"
          :disabled="attrs.disabled"
          :on-exceed="onExceed"
          :on-success="onSuccess"
          :on-remove="onRemove"
          :limit="attrs.limit" 
          :file-list="list || []"
          v-else
        >
          <el-button
            plain
            :class="attrs.type"
            :style="{ width: attrs.width + 'px', height: attrs.height + 'px' }"
          >上传</el-button>
        </el-upload>
      </div>
    </div>
    <div>
      <div v-for="(item,index) in progressList" :key="index" class="progress-wrap">
        <div class="progress-file-name-wrap">
          <i class="el-icon-document"></i>
          <div class="progress-file-name">{{item.name}}</div>
          <div class="progress">{{item.progressPercent}}%</div>
        </div>
        <el-progress :percentage="item.progressPercent" :status="item.status" :show-text="false"></el-progress>
      </div>
    </div>
  </div>
</template>
<script>
import { getFileUrl, getFileName } from "@/utils";
import { FormItemComponent } from "@/mixins.js";
import draggable from "vuedraggable";
import axios from 'axios';
export default {
  mixins: [FormItemComponent],
  components: {
    draggable,
  },
  data() {
    return {
      data: {
        _token: Admin.token,
        ...this.attrs.data
      },
      fileList: [],
      progressPercent: 0,
      uidList: [], //存储每个uid信息数据
      progressList: [], //展示所有上传文件进度的列表
    };
  },
  mounted() {},
  destroyed() {},
  methods: {
    uploadFile(param,config) {
        let axiosConfig = {
            url: this.attrs.action, 
            method: 'post',
            data: param,
        }
        if(config instanceof Object){
            for(let key in config){
                axiosConfig[key] = config[key]
            }
        }
        return axios(axiosConfig)
    },

    handleRequest (data) {
        var params = this.data;

        var file = data.file;

        // 处理重复数据的上传
        var progressListInfo = JSON.parse(JSON.stringify(this.progressList))
        // 处理进度条，当只能上传一条信息的时候
        if(this.attrs.limit && this.attrs.limit == 1) {
          this.progressList = []
        } else {
          progressListInfo.map((item, index) => {
            if(item.name == file.name && item.size == file.size && item.progressPercent < 100) {
              this.progressList.splice(index, 1)
            }
          })
        }

        file.progressPercent = 0;
        this.progressList.push(file);

        let formdata = new FormData()
        for(let i in params){
          formdata.append(i,params[i])
        }
        formdata.append('file', data.file)

        //进度条配置
        let config = {
            onUploadProgress: ProgressEvent => {
                let progressPercent = (ProgressEvent.loaded / ProgressEvent.total * 100)
                // 进行中的进度条
                if(progressPercent<100){
                  var newProgressList = JSON.parse(JSON.stringify(this.progressList))
                  newProgressList.map((item)=>{
                    if(item.uid == file.uid){
                      item.progressPercent = progressPercent.toFixed(1),
                      item.name = file.name,
                      item.size = file.size
                    }
                  })
                  this.progressList = newProgressList;
                }
            }
        }
        this.uploadFile(formdata,config).then(response=>{

            // 成功后的进度条
            var newProgressList = JSON.parse(JSON.stringify(this.progressList))
            newProgressList.map((item)=>{
              if(item.uid == file.uid){
                item.progressPercent = 100
                item.name = file.name
                item.size = file.size
                item.status = 'success'
              }
            })
            this.progressList = newProgressList;

            if (!this.attrs.multiple) {
              this.onChange(response.data.path);
              this.uidList.push({
                uid:file.uid,
                value: response.data.path
              })
            } else {
              let t_value = this._.clone(this.value);
              t_value = this._.isArray(t_value) ? t_value : [];
              t_value.push(this.getObject(response.data.path, 0));
              this.onChange(t_value);
              this.uidList.push({
                uid:file.uid,
                // value: t_value
                value : response.data.path
              })
            }

        }).catch(res=>{
          // 失败时的进度条
          var newProgressList = JSON.parse(JSON.stringify(this.progressList))
          newProgressList.map((item)=>{
            if(item.uid == file.uid){
              item.status = 'exception';
              item.name = file.name;
              item.size = file.size
            }
          })
          this.progressList = newProgressList;
          this.$message.error('上传失败');
          let uid = data.file.uid // 关键作用代码，去除文件列表失败文件
          let idx = this.$refs.upload.uploadFiles.findIndex(item => item.uid === uid) // 关键作用代码，去除文件列表失败文件（uploadFiles为el-upload中的ref值）
          this.$refs.upload.uploadFiles.splice(idx, 1) // 关键作用代码，去除文件列表失败文件
        })
    },

    onDelete(index,item) {
      // 删除下面的进度条
      this.uidList.map((items,index)=>{
        if(items.value == item.path){
          this.uidList.splice(index,1) 
          this.progressList = this.progressList.filter(item=>item.uid!=items.uid)
        }
      })

      if (this._.isArray(this.value)) {
        let t_value = this._.clone(this.value);
        // t_value[index][this.attrs.remove_flag_name] = 1;     
        t_value.splice(index,1);
        this.onChange(t_value);
      } else {
        this.onChange(null);
      }
    },
    onRemove(file, fileList) {},
    onSuccess(response, file, fileList) {
      if (response.code == 200) {
        if (!this.attrs.multiple) {
          this.onChange(response.data.path);
        } else {
          let t_value = this._.clone(this.value);
          t_value = this._.isArray(t_value) ? t_value : [];
          t_value.push(this.getObject(response.data.path, 0));
          this.onChange(t_value);
        }
      } else {
        this.$message.error(response.message);
      }
    },
    onExceed() {
      this.$message.error("超出上传数量，最多上传"+this.attrs.limit+'张');
    },
    getObject(path, id) {
      let keyName = this.attrs.keyName;
      let valueName = this.attrs.valueName;
      let remove_flag_name = this.attrs.remove_flag_name;
      let obj = {};
      if (keyName != null && valueName != null) {
        obj[keyName] = id;
        obj[valueName] = path;
        obj["name"] = getFileName(path);
        obj[remove_flag_name] = 0;
        return obj;
      } else {
        return path;
      }
    },
    getObjectPath(item) {
      let keyName = this.attrs.keyName;
      let valueName = this.attrs.valueName;
      if (keyName != null && valueName != null) {
        return item[valueName];
      }
      return item;
    },
    getObjectKey(item) {
      let keyName = this.attrs.keyName;
      let valueName = this.attrs.valueName;
      if (keyName != null && valueName != null) {
        return item[keyName];
      }
      return item;
    },
    // 拖动排序
    datadragEnd(evt) {
      // console.log('拖动前的索引 :' + evt.oldIndex)
      // console.log('拖动后的索引 :' + evt.newIndex)
      let item = this.value.splice(evt.oldIndex, 1)		// arr删除2，把2给item
      this.value.splice(evt.newIndex, 0, item[0])	// 把2添加到arr最后
      // console.log('this.value',this.value)
      this.onChange(this.value);
      // this.list.map((item, index) => {
      //   item.index = index
      // })
      // console.log(this.list)
      // this.$emit('list', this.list)
    }
  },
  watch: {},

  computed: {
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      };
    },
    list() {
      try {
        if (this._.isArray(this.value)) {
          return this.value
            .filter(item => {
              if (item[this.attrs.remove_flag_name]) {
                return item[this.attrs.remove_flag_name] == 0;
              }
              return true;
            })
            .map((item,index) => {
              let itemPath = this.getObjectPath(item);
              return {
                index: index,
                id: this.getObjectKey(item),
                name: getFileName(itemPath),
                path: itemPath,
                url: getFileUrl(this.attrs.host, itemPath)
              };
            });
        } else {
          if (!this.value) return [];
          let itemPath = this.value;
          if (this._.isObject()) {
            itemPath = this.getObjectPath(this.value);
          }
          return [
            {
              index: 0,
              name: getFileName(itemPath),
              path: itemPath,
              url: getFileUrl(this.attrs.host, itemPath)
            }
          ];
        }
      } catch (error) {
        console.log('error', error)
      }
    },
    urlList() {
      return this.list.map(item => {
        return item.url;
      });
    },
    limit() {
      return this.attrs.limit - this.list.length;
    }
  }
};
</script>
<style lang="scss">
.upload-component {
  display: flex;
  flex-wrap: wrap;
  .upload-images {
    display: flex;
    flex-wrap: wrap;
    .upload-images-item + .upload-images-item {
      margin-left: 10px;
    }
    .upload-images-item {
      position: relative;
      line-height: 1;

      img {
        line-height: 1;
        vertical-align: middle;
      }
      .el-image {
        cursor: zoom-in;
      }
      .el-icon-document-checked {
        font-size: 30px;
      }
      .mask {
        position: absolute;
        transition: all 0.3s ease-in-out;
        opacity: 0;
        background: rgba(0, 0, 0, 0.3);
        color: white;
        font-size: 20px;
        padding: 5px;
        top: 50%;
        left: 50%;
        cursor: pointer;
        transform: translate(-50%, -50%);
      }
      &:hover {
        .mask {
          opacity: 1;
        }
      }
    }
    .upload-show-image {
      border: 1px solid #dcdfe6;
      padding: 2px;
      box-sizing: border-box;
      border-radius: 3px;
    }
  }
  .upload-images-draggable {
    display: flex;
    flex-wrap: wrap;
    margin: 0px !important;
    padding: 0px;
  }
  .upload-block {
    .el-upload-dragger {
      width: unset;
      height: unset;
      border: none;
      border-radius: 0;
    }
    .avatar {
      border-radius: 50%;
    }
  }
}
.progress-wrap {
  width: 270px;
  margin-top: 2px;
  .progress-file-name-wrap {
    display: flex;
    align-items: center;
    line-height: 20px;
    .progress-file-name {
      font-size: 12px;
      flex: 1;
      margin-left: 2px;
      vertical-align: middle;
      text-overflow: ellipsis;
      overflow: hidden;
      display: -webkit-box;
		  -webkit-box-orient: vertical;
		  -webkit-line-clamp: 1;
    }
    .progress {
      font-size: 12px;
      // margin-right: 50px;
      margin-left: 20px;
    }
  }
}
</style>
