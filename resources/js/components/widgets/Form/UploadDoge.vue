<template>
    <div>
        <div class="upload-component">
            <div class="upload-images">
                <draggable class="upload-images-draggable" tag="ul" v-model="list" v-bind="dragOptions"
                           @update="datadragEnd" @start="drag = true" @end="drag = false" style="margin-:-40px;">
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
                            <el-avatar v-else-if="attrs.type == 'avatar'" :size="attrs.width" :src="item.url"/>
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
                    >上传
                    </el-button>
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
                    >上传
                    </el-button>
                </el-upload>
            </div>
        </div>
        <div>
            <div v-for="(item,index) in progressList" :key="index" class="progress-wrap">
                <div class="progress-file-name-wrap">
                    <i class="el-icon-document"></i>
                    <div class="progress-file-name">{{ item.name }}</div>
                    <div class="progress">{{ item.progressPercent }}%</div>
                </div>
                <el-progress :percentage="item.progressPercent" :status="item.status" :show-text="false"></el-progress>
            </div>
        </div>
    </div>
</template>
<script>

import AWS from 'aws-sdk';
import {getFileUrl, getFileName} from "@/utils";
import {FormItemComponent} from "@/mixins.js";
import draggable from "vuedraggable";
import axios from 'axios';
import crypto from 'crypto'

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
    mounted() {
    },
    destroyed() {
    },
    methods: {
        uploadFile(param, config) {
            var aws = new S3();
            aws.upload()
            let axiosConfig = {
                url: this.attrs.action,
                method: 'post',
                data: param,
            }
            if (config instanceof Object) {
                for (let key in config) {
                    axiosConfig[key] = config[key]
                }
            }
            return axios(axiosConfig)
        },

        handleRequest(data) {
            console.log(this.attrs);
            this.$http
                .get(this.attrs.tmpTokenUrl, {
                    params: {
                        path: this.attrs.path
                    }
                })
                .then(res => {
                    console.log(res);
                    let key_data = res.data;
                    var s3 = new AWS.S3({ // 用服务端返回的信息初始化一个 S3 实例
                        region: 'automatic',
                        endpoint: key_data.s3Endpoint,
                        credentials: key_data.credentials,
                        params: {
                            Bucket: key_data.s3Bucket
                        }
                    });
                    //进度
                    // let formdata = new FormData()
                    // for(let i in params){
                    //     formdata.append(i,params[i])
                    // }
                    // formdata.append('file', data.file)

                    var file = data.file;
                    //console.log(file);

                    var dego_file_name = file.name;

                    if (this.attrs.data.uniqueName) {
                        var md5 = crypto.createHash('md5')
                        var ext = file.name.substring(file.name.lastIndexOf('.') + 1);
                        var name_md5 = md5.update(dego_file_name).digest('hex');
                        dego_file_name = name_md5 + '.' + ext;
                    }

                    var targetKey = (this.attrs.data.path || '*').replace('*', dego_file_name);
                    // 服务端返回的这个 keyPrefix 是服务端授权的文件路径名或路径前缀，如果其中包含通配符 *，表示服务端允许客户端自定义
                    // 全部或者一部分的文件路径 Key，我们可以进行自定义，这里用文件本身的名 file.name 来替代，如果 keyPrefix 中不包含 *，
                    // 表示服务端已经完全设置好本次上传的文件路径 Key 了，咱们客户端没办法自定义了，此时这里的 file.name 自然不会起作用

                    // document.getElementById('uploadBtn').disabled = true;
                    file.progressPercent = 0.00;
                    file.targetKey = targetKey;
                    //console.log(file)
                    this.progressList.push(file);
                    let that = this;
                    var startTime = new Date().getTime(), lastTime = new Date().getTime(); // 记录开始时间
                    var lastLoaded = 0, currentSpeed = 0;
                    var s3Upload = s3.upload({
                        Key: targetKey,
                        Body: file,
                        ContentType: file.type // 设置上传后文件的 MIME 类型
                    }).on('httpUploadProgress', function (evt) {
                        console.log(evt);
                        var percent = ((evt.loaded * 100) / evt.total).toFixed(2);
                        var elapsedTime = (new Date().getTime() - startTime) / 1000; // 计算已经过去的时间（秒）
                        var fromLastTime = (new Date().getTime() - lastTime) / 1000;
                        if (fromLastTime > 1) currentSpeed = ((evt.loaded - lastLoaded) / fromLastTime / 1024).toFixed(2);
                        if (fromLastTime > 5) {
                            lastLoaded = evt.loaded;
                            lastTime = new Date().getTime();
                        }
                        let overallSpeed = (evt.loaded / elapsedTime / 1024).toFixed(2); // 计算平均上传速度（KB/s）
                        // document.getElementById('uploadStatus').innerHTML = '进度：' + percent + '%，当前速度：' + currentSpeed + ' KB/s，平均速度：' + overallSpeed + ' KB/s';
                        console.log("进度 : " + percent + "%，当前速度：" + currentSpeed + "，平均速度 : " + overallSpeed + " KB/s");

                        if (percent < 100) {
                            console.log('________')
                            var newProgressList = JSON.parse(JSON.stringify(that.progressList))
                            newProgressList.map((item) => {
                                //console.log('++++++++')
                                if (item.targetKey == evt.key) {
                                    //console.log('========')
                                    item.progressPercent = Number.parseFloat(percent);
                                    item.name = file.name;
                                    item.size = file.size;
                                }
                            })
                            that.progressList = newProgressList;
                        }
                    });
                    s3Upload.send(function (err, data) {
                        // document.getElementById('uploadBtn').disabled = false;
                        if (err) {
                            console.error("上传出错", err);
                            // document.getElementById('uploadStatus').innerHTML = "上传出错：" + err;
                        } else {
                            // 成功后的进度条
                            var newProgressList = JSON.parse(JSON.stringify(that.progressList))
                            newProgressList.map((item) => {
                                //console.log('finish+++')
                                if (item.targetKey == data.Key) {
                                    //console.log('finish+++   0000')
                                    item.progressPercent = 100
                                    item.name = file.name
                                    item.size = file.size
                                    item.status = 'success'
                                }
                            })
                            that.progressList = newProgressList;

                            //console.log("上传成功：" + targetKey, data);
                            that.onChange(data.Key);
                            var cdn = key_data.cdn_host;
                            //console.log("url:" + cdn + targetKey);
                            // document.getElementById('uploadStatus').innerHTML = "上传成功： " + '<a target="_blank" href="' + cdn + targetKey + '">' + targetKey + '</a>';
                        }
                    });
                });
        },

        onDelete(index, item) {
            console.log('onDelete')
            // 删除下面的进度条
            this.uidList.map((items, index) => {
                if (items.value == item.path) {
                    this.uidList.splice(index, 1)
                    this.progressList = this.progressList.filter(item => item.uid != items.uid)
                }
            })

            if (this._.isArray(this.value)) {
                let t_value = this._.clone(this.value);
                // t_value[index][this.attrs.remove_flag_name] = 1;
                t_value.splice(index, 1);
                this.onChange(t_value);
            } else {
                this.onChange(null);
            }
        },
        onRemove(file, fileList) {
            console.log('onDelete')
        },
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
            this.$message.error("超出上传数量，最多上传" + this.attrs.limit + '张');
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
                        .map((item, index) => {
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
