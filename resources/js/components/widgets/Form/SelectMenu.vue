<template>
    <div class="checkBox isMore">
        <div class="checkboxMenu clearfix" :class="toggle ? 'showMore':''">
            <ul class="checkboxMenuUl" ref="checkboxMenuUl">
                <li v-for="(item,index) in attrs.options" :key="index" class="item" @click="changeLi(item,index)">
                    <div class="itemBox" :class="{'active':index==clickIndex}"><span >{{item.label}}</span></div>
                </li>
            </ul>
        </div>
        <div class="more fs0" @click="isToggle" v-if="height > 30">
            <span class="fs14">{{toggle ? '收起分类':'更多分类'}}</span>
            <i class="icon-color" :class="toggle ? 'el-icon-arrow-up':'el-icon-arrow-down'"></i>
        </div>
    </div>
</template>
<script>
// 平铺select选择
import { FormItemComponent } from "@/mixins.js";
export default {
  mixins: [FormItemComponent],
  props: ["attrs", "value", "form_data"],
  data() {
    return {
      toggle: false , //是否展开，默认否
      height: 0,
      clickIndex: 0
    };
  },
  mounted() {
    this.toggle = this.attrs.isMore;
    this.$nextTick(() => {
      this.height = this.$refs.checkboxMenuUl.offsetHeight;
    });
  },
  methods: {
    // 点击收起，展开
    isToggle() {
      this.toggle = !this.toggle;
    },
    // 选择选项
    changeLi(item,index) {
      this.clickIndex = index;
      this.$emit("change", item.value);
    },
  }
};
</script>
<style lang="scss" scoped>
.isMore {
  display: flex;
  -ms-flex-wrap: nowrap;
  flex-wrap: nowrap;
  -webkit-box-align: start;
  -ms-flex-align: start;
  align-items: flex-start;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
  -ms-flex-direction: row;
  flex-direction: row;
  // margin: 4px 0px 0px 0px;
  .checkboxMenu {
    height: 30px;
    overflow: hidden;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    margin: 0px;
    padding: 0px;
    .checkboxMenuUl {
      overflow: hidden;
      -webkit-box-flex: 1;
      -ms-flex: 1;
      flex: 1;
      margin: 0px;
      padding: 0px;
    }
    .item {
      float: left;
      cursor: pointer;
      margin-right: 5px;
      margin-bottom: 5px;
      list-style: none;
      margin: 0;
      padding: 0;
      .itemBox {
        font-size: 14px;
        padding: 0 15px;
        height: 30px;
        line-height: 30px;
        border-radius: 5px;
        overflow: hidden;
        color: #999;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      .active {
        color: #fff;
        // background: #29cc5f!important;
        background: #409EFF !important;
      }
    }
  }
  .showMore {
    height: auto;
  }
  .more {
    width: 80px;
    font-size: 14px;
    // color: #29cc5f;
    color: #409EFF;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    .fs14 {
      font-size: 14px!important;
    }
    .icon-color {
      font-size: 16px;
      font-weight: bold;
      color: #29cc5f;
      cursor: pointer;
      margin-left: 2px;
    }
  }
  .fs0 {
    font-size: 0!important;
  }
}
</style>
