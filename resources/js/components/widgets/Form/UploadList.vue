<template>
  <div>
    <div class="upload-component">
      <div v-for="(item, index) in list" :key="index" style="margin-right: 10px">
        <div
          class="upload-block"
          :class="{ 'ml-10': item.file.length > 0 }"
          v-if="item && item.file.length == 0"
        >
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
            :on-success="
              (response, file, fileList) =>
                onSuccess(response, file, fileList, index)
            "
            :on-remove="onRemove"
            :limit="attrs.limit"
            :file-list="list.file || []"
          >
            <el-button
              plain
              :class="attrs.type"
              :style="{
                width: attrs.width + 'px',
                height: attrs.height + 'px',
              }"
              >Upload</el-button
            >
          </el-upload>
        </div>
        <div v-else class="upload-images">
          <div :key="index" class="upload-images-item">
            <el-image
              title="预览图片"
              v-if="attrs.type == 'image'"
              :src="item.fileList[0].url"
              :style="{
                width: attrs.width + 'px',
                height: attrs.height + 'px',
              }"
              fit="cover"
              class="upload-show-image"
              :preview-src-list="[item.fileList[0].url]"
            />
            <i
              @click="onDelete(index, item)"
              class="mask el-icon-close"
              title="删除图片"
            ></i>
          </div>
        </div>
        <div style="text-align:center">{{item.title}}</div>
      </div>
    </div>
  </div>
</template>
<script>
import { getFileUrl, getFileName } from "@/utils";
import { FormItemComponent } from "@/mixins.js";
import draggable from "vuedraggable";
import axios from "axios";
export default {
  mixins: [FormItemComponent],
  components: {
    draggable,
  },
  data() {
    return {
      data: {
        _token: Admin.token,
        ...this.attrs.data,
      },
      fileList: [],
      progressPercent: 0,
      uidList: [], //存储每个uid信息数据
      progressList: [], //展示所有上传文件进度的列表
      uploadArr: [
        {
          key: "name_1",
        },
        {
          key: "name_2",
        },
        {
          key: "name_3",
        },
      ],
    };
  },
  mounted() {},
  destroyed() {},
  methods: {
    onDelete(index, item) {
      var newList = JSON.parse(JSON.stringify(this.list))
      newList[index].fileList = [];
      newList[index].file = [];
      this.onChange(newList);
    },
    onRemove(file, fileList) {},
    onSuccess(response, file, fileList, index) {
      if (response.code == 200) {
        if (!this.attrs.multiple) {
          let itemPath = response.data.path;
          if (this._.isObject()) {
            itemPath = this.getObjectPath(response.data.path);
          }
          var newList = JSON.parse(JSON.stringify(this.list));
          newList[index].fileList = [
            {
              name: getFileName(itemPath),
              path: itemPath,
              url: getFileUrl(this.attrs.host, itemPath),
            },
          ];
          newList[index].file = [response.data.path];
          this.onChange(newList);
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
      this.$message.error("超出上传数量，最多上传" + this.attrs.limit + "张");
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
  },
  watch: {},

  computed: {
    list() {
      // if (!this.value) {
      //   return [
      //     {
      //       key: "name_1",
      //       fileList: [],
      //       file: [],
      //     },
      //     {
      //       key: "name_2",
      //       fileList: [],
      //       file: [],
      //     },
      //     {
      //       key: "name_3",
      //       fileList: [],
      //       file: [],
      //     },
      //   ];
      // } else {
        return this.value;
      // }
    },
    limit() {
      return this.attrs.limit - this.list.length;
    },
  },
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
