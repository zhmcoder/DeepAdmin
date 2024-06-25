(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=script&lang=js":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _mixins_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/mixins.js */ "./resources/js/mixins.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var js_cookie__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! js-cookie */ "./node_modules/js-cookie/src/js.cookie.js");
/* harmony import */ var js_cookie__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(js_cookie__WEBPACK_IMPORTED_MODULE_2__);



/* harmony default export */ __webpack_exports__["default"] = ({
  components: {},
  mixins: [_mixins_js__WEBPACK_IMPORTED_MODULE_0__["FormItemComponent"]],
  props: ['defaultPropValues', 'attrs'],
  data: function data() {
    return {
      randomEditorId: 'ueditor',
      defaultValue: '',
      editorHtml: '111',
      ueditor: null,
      editorHomeUrl: null
    };
  },
  updated: function updated() {},
  mounted: function mounted() {
    var _this2 = this;
    this.editorHomeUrl = this.attrs.jsBasePath ? this.attrs.jsBasePath : window.location.origin + '/UEditor/';
    // if (this.editorHomeUrl.includes('localhost')) {
    //   this.editorHomeUrl = 'https://plm.zdapk.cn/UEditor/'
    // }
    this.randomEditorId = 'ueditor_' + parseInt(Math.random() * 1000);
    this.loadCDNJS("".concat(this.editorHomeUrl, "ueditor.config.js"));
    this.loadCDNJS("".concat(this.editorHomeUrl, "ueditor.all.js?v=2"), true).then(function (_) {
      _this2.initEditor();
    });
    this.$nextTick(function (_) {});
  },
  watch: {
    value: function value(_value) {
      if (_value !== this.ueditor.getContent() && _value) {
        this.ueditor.setContent(_value);
      }
    }
  },
  unmounted: function unmounted() {
    if (this.ueditor) this.ueditor.destroy();
  },
  methods: {
    initEditor: function initEditor() {
      var _this = this;
      console.log('###$1', Admin, this.attrs);
      if (UE.getEditor) {
        this.ueditor = UE.getEditor(this.randomEditorId, {
          UEDITOR_HOME_URL: this.editorHomeUrl,
          initialContent: this.value || this.attrs.componentValue || '',
          allHtmlEnabled: true,
          headers: {
            // 'Content-Type': 'multipart/form-data',
            _token: Admin.token
            // 'xsrf-token': Cookies.get('XSRF-TOKEN')
          },

          // toolbars: [this.attrs.menus],
          zIndex: this.attrs.zIndex,
          serverUrl: window.location.origin + '/' + this.attrs.uploadImgServer,
          readonly: this.attrs.disabled,
          axios: axios__WEBPACK_IMPORTED_MODULE_1___default.a
        });
        this.ueditor.ready(function () {
          _this.ueditor.setContent(_this.value || '');
          _this.ueditor.addListener("contentchange", function () {
            _this.onChange(_this.ueditor.getContent());
          });
        });
      }
    },
    loadCDNJS: function loadCDNJS(url, defer) {
      return new Promise(function (resolve, reject) {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = url;
        if (defer) script.defer = 'defer';
        if (script.readyState) {
          // 仅限IE
          script.onreadystatechange = function () {
            if (script.readyState == "loaded" || script.readyState == "complete") {
              script.onreadystatechange = null;
              resolve();
            }
          };
        } else {
          // 其他浏览器
          script.onload = function () {
            console.log('load', url, 'success');
            resolve();
          };
        }
        document.getElementsByTagName("head")[0].appendChild(script);
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=template&id=3464b270":
/*!********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib/loaders/templateLoader.js??ref--6!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=template&id=3464b270 ***!
  \********************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "wangeditor-main flex-sub"
  }, [_vm.attrs.component ? _c("div", [_c(_vm.attrs.component.componentName, {
    tag: "component",
    attrs: {
      attrs: _vm.attrs.component,
      editor: _vm.editor
    }
  })], 1) : _vm._e(), _vm._v(" "), _c("div", {
    staticStyle: {
      width: "100%"
    },
    attrs: {
      id: _vm.randomEditorId
    }
  })]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=style&index=0&id=3464b270&lang=css":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=style&index=0&id=3464b270&lang=css ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.edui-default .edui-toolbar {\r\n    line-height: initial;\n}\n.edui-default .edui-toolbar .edui-combox .edui-combox-body {\r\n    line-height: initial;\n}\r\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=style&index=0&id=3464b270&lang=css":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--5-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--5-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=style&index=0&id=3464b270&lang=css ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--5-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--5-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./BaiduEditor.vue?vue&type=style&index=0&id=3464b270&lang=css */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=style&index=0&id=3464b270&lang=css");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./resources/js/components/widgets/Form/BaiduEditor.vue":
/*!**************************************************************!*\
  !*** ./resources/js/components/widgets/Form/BaiduEditor.vue ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _BaiduEditor_vue_vue_type_template_id_3464b270__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./BaiduEditor.vue?vue&type=template&id=3464b270 */ "./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=template&id=3464b270");
/* harmony import */ var _BaiduEditor_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./BaiduEditor.vue?vue&type=script&lang=js */ "./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _BaiduEditor_vue_vue_type_style_index_0_id_3464b270_lang_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./BaiduEditor.vue?vue&type=style&index=0&id=3464b270&lang=css */ "./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=style&index=0&id=3464b270&lang=css");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _BaiduEditor_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _BaiduEditor_vue_vue_type_template_id_3464b270__WEBPACK_IMPORTED_MODULE_0__["render"],
  _BaiduEditor_vue_vue_type_template_id_3464b270__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/widgets/Form/BaiduEditor.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=script&lang=js":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=script&lang=js ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_BaiduEditor_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./BaiduEditor.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_BaiduEditor_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=style&index=0&id=3464b270&lang=css":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=style&index=0&id=3464b270&lang=css ***!
  \**********************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_BaiduEditor_vue_vue_type_style_index_0_id_3464b270_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--5-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--5-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./BaiduEditor.vue?vue&type=style&index=0&id=3464b270&lang=css */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=style&index=0&id=3464b270&lang=css");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_BaiduEditor_vue_vue_type_style_index_0_id_3464b270_lang_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_BaiduEditor_vue_vue_type_style_index_0_id_3464b270_lang_css__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_BaiduEditor_vue_vue_type_style_index_0_id_3464b270_lang_css__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_5_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_5_2_node_modules_vue_loader_lib_index_js_vue_loader_options_BaiduEditor_vue_vue_type_style_index_0_id_3464b270_lang_css__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=template&id=3464b270":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=template&id=3464b270 ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_BaiduEditor_vue_vue_type_template_id_3464b270__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ref--6!../../../../../node_modules/vue-loader/lib??vue-loader-options!./BaiduEditor.vue?vue&type=template&id=3464b270 */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/widgets/Form/BaiduEditor.vue?vue&type=template&id=3464b270");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_BaiduEditor_vue_vue_type_template_id_3464b270__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_BaiduEditor_vue_vue_type_template_id_3464b270__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);