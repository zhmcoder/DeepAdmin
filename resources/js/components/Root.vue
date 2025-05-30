<template>
  <div class="admin-main">
    <el-container class="admin-layout">
      <el-aside
        ref="contentSide"
        class="content-side"
        :class="{
          'content-side-fixed': fixedSide,
          'side-dark': isDark ? true : false,
          'side-blue': isBlue ? true : false,
        }"
        :width="isCollapsed ? '64px' : '200px'"
      >
        <div class="content-side-logo">
          <template v-if="pageData.logoShow">
            <template v-if="isWhite">
              <template v-if="pageData.logoLight">
                <img v-if="isCollapsed" :src="pageData.logoMiniLight" />
                <img v-else :src="pageData.logoLight" />
              </template>
              <template v-else>
                <img v-if="isCollapsed" src="../assets/logo-mini-light.svg" />
                <img v-else src="../assets/logo-light.svg" />
              </template>
            </template>
            <template v-else-if="isDark">
              <template v-if="pageData.logo">
                <img v-if="isCollapsed" :src="pageData.logoMini" />
                <img v-else :src="pageData.logo" />
              </template>
              <template v-else>
                <img v-if="isCollapsed" src="../assets/logo-mini.svg" />
                <img v-else src="../assets/logo.svg" />
              </template>
            </template>
            <template v-else-if="isBlue">
              <template v-if="pageData.logo">
                <img v-if="isCollapsed" :src="pageData.logoMini" />
                <img v-else :src="pageData.logo" />
              </template>
              <template v-else>
                <img v-if="isCollapsed" src="../assets/logo-mini.svg" />
                <img v-else src="../assets/logo.svg" />
              </template>
            </template>
          </template>
          <h1 v-if="!isCollapsed && pageData.name">{{ pageData.name }}</h1>
        </div>
        <el-scrollbar wrap-class="scrollbar-wrapper">
          <el-menu
            :default-active="route"
            :collapse="isCollapsed"
            :background-color="
              isDark
                ? '#1d1e23'
                : isWhite
                ? ''
                : isBlue
                ? 'rgb(48, 65, 86)'
                : ''
            "
            :text-color="
              isDark
                ? '#ffffff'
                : isWhite
                ? ''
                : isBlue
                ? 'rgb(191, 203, 217)'
                : ''
            "
            :collapse-transition="false"
            :unique-opened="pageData.uniqueOpened"
            :router="true"
            @select="onSelect"
            v-if="show"
          >
            <template v-for="menu in pageData.menu">
              <el-submenu
                :index="menu.route"
                :key="menu.id"
                v-if="menu.children && menu.children.length > 0"
              >
                <template slot="title">
                  <i :class="menu.icon" v-if="menu.icon" size="16"></i>
                  <span slot="title">{{ menu.title }}</span>
                </template>
                <template v-for="children in menu.children">
                  <MenuItem
                    :menu="children"
                    :key="children.id"
                    :is_collapsed="isCollapsed"
                  />
                </template>
              </el-submenu>
              <el-menu-item
                :index="menu.route"
                :key="menu.id"
                :route="menu.route"
                v-else
              >
                <i :class="menu.icon" v-if="menu.icon" size="16"></i>
                <span slot="title">{{ menu.title }}</span>
              </el-menu-item>
            </template>
          </el-menu>
        </el-scrollbar>
      </el-aside>
      <el-container
        :class="{
          'el-container-fixed': fixedSide,
          'el-container-fixed-collapsed': isCollapsed,
        }"
      >
        <el-header
          :style="{ padding: 0 }"
          class="layout-header-bar"
          :class="{
            'layout-header-bar-dark': !isDarkHeader,
            'layout-header-bar-blue': !isDarkHeader && isBlue,
            'layout-header-bar-fixed': fixedHeader,
            'layout-header-bar-fixed-collapsed': isCollapsed,
          }"
          height="55px"
        >
          <div class="layout-header-l">
            <div class="layout-header-trigger hover" @click="collapsedSide">
              <i
                class="el-icon-s-fold fs-20 menu-icon"
                :class="{ 'rotate-icon': isCollapsed }"
              />
            </div>
            <div class="layout-header-breadcrumb">
              <el-breadcrumb separator="/">
                <el-breadcrumb-item :to="{ path: '/' }"
                  >首页
                </el-breadcrumb-item>
                <!-- navBar -->
                <template v-for="menu in pageData.menuList">
                  <el-breadcrumb-item
                    v-if="menu.route == route"
                    :key="menu.route"
                    >{{ menu.title }}
                  </el-breadcrumb-item>
                </template>
              </el-breadcrumb>
            </div>
          </div>
          <div class="layout-header-r">
            <div class="flex align-center"></div>
            <el-tooltip class="item" effect="dark" content="刷新">
              <div
                @click="pageReload"
                class="layout-header-trigger layout-header-trigger-min hover"
              >
                <i class="el-icon-refresh-right icon-btn"></i>
              </div>
            </el-tooltip>
            <div class="layout-header-trigger layout-header-trigger-min hover">
              <el-dropdown>
                <div class="layout-header-user">
                  <el-avatar :src="pageData.user.avatar" :size="25" />
                  <span class="layout-header-user-name">{{
                    pageData.user.name
                  }}</span>
                </div>
                <el-dropdown-menu slot="dropdown">
                  <a @click="updatePsd">
                    <el-dropdown-item>
                      <i class="el-icon-lock" size="18" />
                      <span>修改密码</span>
                    </el-dropdown-item>
                  </a>
                  <a @click="onLogout">
                    <el-dropdown-item>
                      <i class="el-icon-right" size="18" />
                      <span>退出登录</span>
                    </el-dropdown-item>
                  </a>
                </el-dropdown-menu>
              </el-dropdown>
            </div>
            <div
              @click="showAdminSet = true"
              class="layout-header-trigger layout-header-trigger-min hover"
            >
              <i class="el-icon-setting icon-btn"></i>
            </div>
          </div>
        </el-header>
        <el-main :class="{ 'el-main-fixed': fixedHeader }">
          <div class="layout-content-main">
            <router-view></router-view>
          </div>
        </el-main>
        <el-footer class="admin-footer" height="auto">
          <div ref="rootFooter">
            <div class="footer-links">
              <el-link
                v-for="(item, index) in pageData.footerLinks"
                :key="index"
                type="text"
                :href="item.href"
                target="_blank"
                :underline="false"
                >{{ item.title }}
              </el-link>
            </div>
            <div v-html="pageData.copyright"></div>
          </div>
        </el-footer>
      </el-container>
    </el-container>
    <el-backtop></el-backtop>
    <el-drawer :visible.sync="showAdminSet" size="250px">
      <div style="padding: 0 10px">
        <el-divider>主题风格</el-divider>
        <div>
          <el-badge type="success" is-dot :hidden="!isWhite">
            <div>
              <el-tooltip content="亮色菜单风格" placement="top">
                <img
                  @click="
                    isWhite = true;
                    isBlue = false;
                    isDark = false;
                  "
                  class="hover"
                  src="../assets/menu-light.svg"
                />
              </el-tooltip>
            </div>
          </el-badge>
          <el-badge type="success" is-dot :hidden="!isDark">
            <div class="ml-20">
              <el-tooltip content="暗色菜单风格" placement="top">
                <img
                  @click="
                    isDark = true;
                    isWhite = false;
                    isBlue = false;
                  "
                  class="hover"
                  src="../assets/menu-dark.svg"
                />
              </el-tooltip>
            </div>
          </el-badge>
          <el-badge type="success" is-dot :hidden="!isBlue">
            <div class="ml-20">
              <el-tooltip content="蓝色菜单风格" placement="top">
                <img
                  @click="
                    isBlue = true;
                    isDark = false;
                    isWhite = false;
                  "
                  class="hover"
                  src="../assets/menu-dark.svg"
                />
              </el-tooltip>
            </div>
          </el-badge>
        </div>
        <div class="mt-30">
          <el-badge type="success" is-dot :hidden="!isDarkHeader">
            <div @click="isDarkHeader = true">
              <el-tooltip content="亮色顶栏风格" placement="top">
                <img class="hover" src="../assets/nav-light.svg" />
              </el-tooltip>
            </div>
          </el-badge>
          <el-badge type="success" is-dot :hidden="isDarkHeader">
            <div class="ml-20" @click="isDarkHeader = false">
              <el-tooltip content="暗色顶栏风格" placement="top">
                <img class="hover" src="../assets/nav-dark.svg" />
              </el-tooltip>
            </div>
          </el-badge>
        </div>
        <el-divider>导航设置</el-divider>

        <div class="setting-item">
          <span>固定顶栏</span>
          <span>
            <el-switch v-model="fixedHeader"></el-switch>
          </span>
        </div>
        <div class="setting-item">
          <span>固定侧边栏</span>
          <span>
            <el-switch v-model="fixedSide"></el-switch>
          </span>
        </div>
      </div>
    </el-drawer>
    <vue-progress-bar></vue-progress-bar>
    <message-dialog :attrs="dialog_attrs" v-if="isMsgDialogShow" />

    <!--  -->
    <el-dialog
      title="修改密码"
      width="400px"
      :visible.sync="dialogUpdatePsdVisible"
      append-to-body
      destroy-on-close
    >
      <!-- :rules="formRules" -->
      <el-form :model="passwordInfo" ref="psdForm" label-width="110px">
        <el-form-item prop="password" label="新密码">
          <el-input
            type="password"
            v-model="passwordInfo.password"
            style="width: 250px"
            placeholder="请输入新密码"
          ></el-input>
        </el-form-item>
        <el-form-item prop="password_confirm" label="确认密码">
          <el-input
            type="password"
            v-model="passwordInfo.password_confirm"
            style="width: 250px"
            placeholder="请确认密码"
          ></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogUpdatePsdVisible = false">取 消</el-button>
        <el-button :loading="btnLodgin" type="primary" @click="setPsd"
          >确 定</el-button
        >
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { flattenDeepChild } from "../utils";
import MessageDialog from "./grid/MessageDialog.vue";
import Vue from "vue";

export default {
  components: { MessageDialog },
  props: {
    pageData: Object,
  },
  data() {
    return {
      fixedSide: localStorage.getItem("fixedSide")
        ? localStorage.getItem("fixedSide") == "true"
        : true,
      fixedHeader: localStorage.getItem("fixedHeader")
        ? localStorage.getItem("fixedHeader") == "true"
        : true,
      isCollapsed: localStorage.getItem("isCollapsed")
        ? localStorage.getItem("isCollapsed") == "true"
        : false,
      isDark: localStorage.getItem("isDark") == "true" ? true : false,
      isDarkHeader: localStorage.getItem("isDarkHeader")
        ? localStorage.getItem("isDarkHeader") == "true"
        : true,
      showAdminSet: false,
      route: "/",
      query: {},
      isBlue: localStorage.getItem("isBlue") == "true" ? true : false,
      isWhite: localStorage.getItem("isWhite") == "true" ? true : false,
      dialog_attrs: {
        title: "系统提示",
        content: "系统提示内容",
        footer: true,
        center: true,
        okText: "我知道了",
        okShow: true,
      },
      isMsgDialogShow: false,
      dialogUpdatePsdVisible: false,
      btnLodgin: false,
      passwordInfo: {
        password: null,
        password_confirm: null,
      },
      formRules: {
        password: [
          { required: true, message: "请输入密码", trigger: "blur" },
          {
            pattern: /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/,
            message: "请输入6位以上的数字和字母组合密码",
            trigger: "blur",
          },
        ],
        password_confirm: [
          { required: true, message: "请确认密码", trigger: "blur" },
          {
            pattern: /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/,
            message: "请输入6位以上的数字和字母组合密码",
            trigger: "blur",
          },
        ],
      },
      show: true
    };
  },
  created() {
    if (
      (!localStorage.getItem("isDark") && !localStorage.getItem("isBlue")) ||
      (localStorage.getItem("isDark") == "false" &&
        localStorage.getItem("isBlue") == "false")
    ) {
      this.isWhite = true;
      localStorage.setItem("isWhite", "true");
    }
      if(this.pageData.menuIsCollapsed !== null
          && localStorage.getItem("isCollapsed") === null){
          this.isCollapsed = this.pageData.menuIsCollapsed
          console.log('reload menuIsCollapsed')
      }
      console.log(this.pageData.menuIsCollapsed)
  },
  mounted() {
    //监听路由变动
    this.$bus.on("route-after", (to) => {
      this.route = to.path;
      this.query = to.query;
      let queryKey = [];
      _.forEach(this.query, function (value, key) {
        queryKey.push(key + "=" + value);
      });
      this.route =
        this.route + (queryKey.length > 0 ? "?" : "") + queryKey.join("&");
      let checkLength = this.menuRoutes.filter((item) => {
        return this.route == item;
      }).length;
      if (checkLength <= 0) {
        this.menuRoutes.map((item) => {
          if (to.path.indexOf(item) >= 0) {
            this.route = item;
          }
        });
      }
    });
    //监听message事件
    this.$bus.on("message", ({ type, message }) => {
      this.$message[type](message);
    });
    this.$nextTick(() => {
      window.rootFooterHeight = this.$refs.rootFooter.offsetHeight + 60;
    });

    this.$bus.on("msgDialogShow", ({ isMsgDialogShow, msgDialog }) => {
      if (msgDialog) {
        this.dialog_attrs.title = msgDialog.title;
        this.dialog_attrs.content = msgDialog.content;
        this.dialog_attrs.okText = msgDialog.okText;
        if (msgDialog.width) {
          this.dialog_attrs.width = msgDialog.width;
        } else {
          this.dialog_attrs.width = "400px";
        }
      }
      this.isMsgDialogShow = isMsgDialogShow;
    });
  },
  destroyed() {
    this.$bus.off("route-after");
    this.$bus.off("message");
  },
  computed: {
    menuitemClasses() {
      return ["menu-item", this.isCollapsed ? "collapsed-menu" : ""];
    },
    menuRoutes() {
      return flattenDeepChild(this.pageData.menu, "children", "route");
    },
  },
  methods: {
    pageReload() {
      this.$bus.emit("pageReload");
    },
    collapsedSide() {
      this.isCollapsed = !this.isCollapsed;
    },
    onLogout() {
      this.$confirm("您确定退出登录当前账户吗？", "退出登录确认").then(() => {
        window.location.href = this.pageData.url.logout;
      });
    },
    onSelect() {
        if (this.isCollapsed) {
           this.show = false
           Vue.nextTick(() => {
               this.show = true
           })
        }
    },
    // 修改密码
    updatePsd() {
      this.dialogUpdatePsdVisible = true;
    },
    //添加用户
    async setPsd() {
      this.$refs["psdForm"].validate(async (valid) => {
        if (valid) {
          this.btnLodgin = true;
          this.$http
            .post(this.pageData.resetPwdUrl, this.passwordInfo)
            .then((res) => {
              if (res.code == 200) {
                this.btnLodgin = false;
                this.dialogUpdatePsdVisible = false;
                window.location.href = this.pageData.url.logout;
              }
            })
            .finally(() => {
              this.btnLodgin = false;
            });
        } else {
          return false;
        }
      });
    },
  },
  watch: {
    $route() {},
    fixedSide(val) {
      localStorage.setItem("fixedSide", val);
    },
    fixedHeader(val) {
      localStorage.setItem("fixedHeader", val);
    },
    isCollapsed(val) {
      localStorage.setItem("isCollapsed", val);
    },
    isDark(val) {
      console.log("hhh");
      localStorage.setItem("isDark", val);
    },
    isWhite(val) {
      localStorage.setItem("isWhite", val);
    },
    isBlue(val) {
      localStorage.setItem("isBlue", val);
    },
    isDarkHeader(val) {
      localStorage.setItem("isDarkHeader", val);
    },
  },
};
</script>

<style lang="scss">
$header-bar-height: 55px;
.admin-layout {
  min-height: 100vh;
}

.ivu-layout-sider {
  min-height: 100vh;

  .ivu-layout-sider-children {
    height: 100%;
    padding-top: 0.1px;
    margin-top: -0.1px;
  }
}

.content-side {
  min-height: 100vh;
  background: #fff;
  box-shadow: 2px 0 8px 0 rgba(29, 35, 41, 0.05);
  position: relative;
  transition: all 0.3s ease-in-out;
  z-index: 13;
  display: flex;
  flex-direction: column;

  .el-menu {
    border-right: none;
  }

  .content-side-logo {
    height: $header-bar-height;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;

    img {
      height: 30px;
      object-fit: cover;
      vertical-align: middle;
    }

    h1 {
      display: inline-block;
      margin: 0 0 0 5px;
      color: #666;
      font-weight: 600;
      font-size: 20px;
      vertical-align: middle;
      animation: fade-in;
      animation-duration: 0.3s;
    }
  }

  .el-scrollbar {
    flex: 1;

    .scrollbar-wrapper {
      overflow-x: hidden;
    }
  }
}

.el-aside {
  z-index: 1000;
}

.content-side-fixed {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

.el-container-fixed {
  margin-left: 200px;
  transition: all 0.3s ease-in-out;
}

.el-container-fixed-collapsed {
  margin-left: 60px;
}

.side-dark {
  background: #1d1e23;
  box-shadow: 2px 0 6px rgba(0, 21, 41, 0.35);

  .content-side-logo {
    h1 {
      color: #ffffff;
    }
  }
}

.layout-header-bar {
  width: 100%;
  background: #fff;
  padding: 0;
  box-shadow: 0 1px 4px rgba(0, 21, 41, 0.08);
  transition: all 0.3s ease-in-out;
  z-index: 3;
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: $header-bar-height;
  line-height: $header-bar-height;

  .hover {
    &:hover {
      background-color: #f7f7f7;
    }
  }

  .layout-header-l {
    display: flex;
    height: $header-bar-height;

    .layout-header-trigger {
      width: $header-bar-height;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;

      .menu-icon {
        transition-duration: 0.3s;
        transform: rotate(0deg);
      }

      .rotate-icon {
        transform: rotate(180deg);
      }
    }

    .layout-header-breadcrumb {
      margin-left: 10px;
      display: flex;
      align-items: center;
    }
  }

  .layout-header-r {
    height: $header-bar-height;
    display: flex;
    align-items: center;

    .layout-header-trigger {
      width: $header-bar-height;
      height: $header-bar-height;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .layout-header-trigger-min {
      width: auto;
      padding: 0 12px;
    }

    .layout-header-user {
      display: flex;
      align-items: center;

      .layout-header-user-name {
        margin-left: 5px;
      }
    }

    .icon-btn {
      font-size: 18px;
    }

    .icon-btn-mini {
      font-size: 14px;
    }
  }
}

.layout-header-bar-dark {
  background: #1d1e23;
  color: white;

  .el-dropdown {
    color: white;
  }

  .el-breadcrumb__inner {
    color: #ffffffb3 !important;
  }

  .el-breadcrumb__item:last-child .el-breadcrumb__inner {
    color: white !important;
  }

  .hover {
    &:hover {
      background-color: #1d1e23;
    }
  }
}

.layout-header-bar-fixed {
  position: fixed;
  right: 0;
  top: 0;
  left: 200px;
  width: auto;
  transition: all 0.3s ease-in-out;
  z-index: 999;
}

.layout-header-bar-fixed-collapsed {
  left: 60px;
}

.el-main {
  padding: 0;
}

.el-main-fixed {
  margin-top: $header-bar-height;
}

.layout-content-main {
  margin: 0px;

  .layout-page-header {
    margin: -15px -15px 0;
    padding: 15px 15px 0 15px;
    margin-bottom: 15px;
    background: #fff;
    border-bottom: 1px solid #e8eaec;

    .layout-page-header-title {
      color: #17233d;
      font-weight: 500;
      font-size: 20px;
      margin-bottom: 8px;
    }

    .layout-page-header-description {
      font-size: 14px;
      margin-bottom: 15px;
    }
  }
}

.admin-footer {
  text-align: center;
  color: #808695;
  margin: 10px 0;

  .footer-links {
    a {
      color: unset;

      span {
        margin: 5px 20px;
      }
    }
  }
}

.setting-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 0;
}

.el-drawer__header {
  margin-bottom: 0;
}

.side-blue {
  background-color: rgb(48, 65, 86);
  box-shadow: 2px 0 6px rgba(48, 65, 86, 0.35);
  // .content-side-logo{
  //   background: #2b2f3a;
  // }
  .content-side-logo {
    h1 {
      color: rgb(191, 203, 217);
    }
  }

  .el-menu-item:hover {
    background: rgb(38, 52, 69) !important;
  }

  .el-submenu__title:hover {
    background: rgb(38, 52, 69) !important;
  }

  .el-submenu .el-menu-item {
    background-color: #1f2d3d !important;
  }

  .el-submenu .el-menu-item:hover {
    background-color: #001528 !important;
  }
}

.layout-header-bar-blue {
  background: rgb(48, 65, 86);
  color: white;

  .el-dropdown {
    color: white;
  }

  .el-breadcrumb__inner {
    color: #ffffffb3 !important;
  }

  .el-breadcrumb__item:last-child .el-breadcrumb__inner {
    color: white !important;
  }

  .hover {
    &:hover {
      background-color: #1d1e23;
    }
  }
}
</style>

