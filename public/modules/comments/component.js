(self["webpackChunk"] = self["webpackChunk"] || []).push([["comments"],{

/***/ "./modules/comments/resources/js/components.js":
/*!*****************************************************!*\
  !*** ./modules/comments/resources/js/components.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

Vue.component('comment-product', (__webpack_require__(/*! ./components/CommentProduct */ "./modules/comments/resources/js/components/CommentProduct.vue")["default"]));
Vue.component('comment-page', (__webpack_require__(/*! ./components/CommentPage */ "./modules/comments/resources/js/components/CommentPage.vue")["default"]));

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./modules/comments/resources/js/components/CommentPage.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./modules/comments/resources/js/components/CommentPage.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: "CommentPage",
  props: ['auth'],
  data: function data() {
    return {
      dialog: false,
      comments: null,
      reply_to: null,
      reply_content: null,
      product_id: null,
      pagination_page: null,
      pagination_total: null,
      pagination_perPage: null,
      content: '',
      contentRules: [function (v) {
        return !!v || 'متن پیام نمی تواند خالی باشد!';
      }, function (v) {
        return v.length <= 500 || 'حداکثر تعداد کاراکتر 500';
      }]
    };
  },
  mounted: function mounted() {
    this.get_comments();
  },
  methods: {
    get_comments: function get_comments() {
      var _this = this;

      this.axios.get(this.$siteUrl + '/admin/get_comments').then(function (response) {
        _this.pagination_page = response.data.current_page;
        _this.pagination_total = response.data.last_page;
        _this.pagination_perPage = response.data.per_page;

        if (response.data.data.length > 0) {
          _this.comments = response.data.data;
        }
      });
    },
    next: function next(page) {
      var _this2 = this;

      this.axios.get(this.$siteUrl + '/admin/get_comments?page=' + page).then(function (response) {
        _this2.pagination_page = response.data.current_page;
        _this2.pagination_total = response.data.last_page;
        _this2.pagination_perPage = response.data.per_page;

        if (response.data.data.length > 0) {
          _this2.comments = response.data.data;
          var comments_body = document.getElementById("comment_tag");
          comments_body.scrollIntoView({
            behavior: 'smooth'
          });
        }
      });
    },
    del_row: function del_row(url) {
      this.$root.$emit('send_delete_request', url);
      this.dialog = false;
    },
    set_status: function set_status(id, status) {
      var _this3 = this;

      this.$root.$emit('show_progress');
      var formData = new FormData();
      formData.append('comment_id', id);
      formData.append('status', status);
      formData.append('request-type', 'axios');
      this.axios.post(this.$siteUrl + '/admin/comments/set_status', formData).then(function (response) {
        _this3.$root.$emit('hide_progress');

        if (response.data['message'] !== undefined) {
          var type = response.data['status'] === undefined ? 'success' : response.data['status'];

          _this3.$root.$emit('showResponse', response.data['message'], type);
        } else {
          _this3.$root.$emit('showResponse', 'درگاه مورد نظر با موفقیت تغییر وضعیت داد.', 'success');
        }
      });
      this.get_comments();
    },
    add_answer: function add_answer(value) {
      this.dialog = true;

      if (value.parent_id === 0) {
        this.parent_id = value.id;
        this.reply_to = value.name;
        this.reply_content = value.content;
      } else {
        this.parent_id = value['get_parent']['id'];
        this.reply_to = value['get_parent']['name'];
        this.reply_content = value['get_parent']['content'];
      }

      this.product_id = value.product_id;
      this.content = '';
    },
    send_comment: function send_comment() {
      var _this4 = this;

      if (this.$refs.form.validate()) {
        if (this.auth) {
          this.name = this.auth['name'];
          this.email = this.auth['email'];
          this.user_id = this.auth['id'];
        }

        this.$root.$emit('show_progress');
        var formData = new FormData();
        formData.append('product_id', this.product_id);
        formData.append('parent_id', this.parent_id);
        formData.append('name', this.name);
        formData.append('email', this.email);
        formData.append('content', this.content);
        formData.append('user_id', this.user_id);
        formData.append('status', '1');
        formData.append('request-type', 'axios');
        this.axios.post(this.$siteUrl + '/add_comment', formData).then(function (response) {
          _this4.$root.$emit('hide_progress');

          if (response.data['message'] !== undefined) {
            var type = response.data['status'] === undefined ? 'success' : response.data['status'];

            _this4.$root.$emit('showResponse', response.data['message'], type);
          } else {
            _this4.comment_box = false;

            _this4.$root.$emit('showResponse', 'نظر با موفقیت ثبت شد.', 'success');
          }
        });
        this.get_comments();
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./modules/comments/resources/js/components/CommentProduct.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./modules/comments/resources/js/components/CommentProduct.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: "CommentProduct",
  props: ['product_id', 'auth'],
  data: function data() {
    return {
      comment_box: false,
      comments: null,
      user_id: 0,
      reply: false,
      reply_to: null,
      reply_content: null,
      parent_id: 0,
      pagination_page: null,
      pagination_total: null,
      pagination_perPage: null,
      valid: true,
      name: '',
      nameRules: [function (v) {
        return !!v || 'نام و نام خانوادگی نمی تواند خالی باشد!';
      }],
      email: '',
      emailRules: [function (v) {
        return !!v || 'ایمیل نمی تواند خالی باشد!';
      }, function (v) {
        return /.+@.+\..+/.test(v) || 'ایمیل باید معتبر باشد!';
      }],
      content: '',
      contentRules: [function (v) {
        return !!v || 'متن پیام نمی تواند خالی باشد!';
      }, function (v) {
        return v.length <= 500 || 'حداکثر تعداد کاراکتر 500';
      }]
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.axios.get(this.$siteUrl + '/get_comment_product/' + this.product_id).then(function (response) {
      _this.pagination_page = response.data.current_page;
      _this.pagination_total = response.data.last_page;
      _this.pagination_perPage = response.data.per_page;

      if (response.data.data.length > 0) {
        _this.comments = response.data.data;
      } else {
        _this.comment_box = true;
      }
    });
  },
  methods: {
    show_send_comment: function show_send_comment() {
      this.comment_box = !this.comment_box;
      this.parent_id = 0;
      this.reply = null;
      this.reply_to = null;
      this.reply_content = null;
      this.name = '';
      this.email = '';
      this.content = '';
    },
    send_comment: function send_comment() {
      var _this2 = this;

      if (this.$refs.form.validate()) {
        if (this.auth) {
          this.name = this.auth['name'];
          this.email = this.auth['email'];
          this.user_id = this.auth['id'];
        }

        this.$root.$emit('show_progress');
        var formData = new FormData();
        formData.append('product_id', this.product_id);
        formData.append('parent_id', this.parent_id);
        formData.append('name', this.name);
        formData.append('email', this.email);
        formData.append('content', this.content);
        formData.append('user_id', this.user_id);
        formData.append('request-type', 'axios');
        this.axios.post(this.$siteUrl + '/add_comment', formData).then(function (response) {
          _this2.$root.$emit('hide_progress');

          if (response.data['message'] !== undefined) {
            var type = response.data['status'] === undefined ? 'success' : response.data['status'];

            _this2.$root.$emit('showResponse', response.data['message'], type);
          } else {
            _this2.comment_box = false;

            _this2.$root.$emit('showResponse', 'نظر با موفقیت ثبت شد و بعد از تایید مدیر نمایش داده می شود.', 'success');
          }
        });
      }
    },
    add_answer: function add_answer(value) {
      this.comment_box = true;
      this.reply = true;
      this.parent_id = value.id;
      this.reply_to = value.name;
      this.reply_content = value.content;
      this.name = '';
      this.email = '';
      this.content = '';
      var comments_body = document.getElementById("comments-body");
      comments_body.scrollIntoView({
        behavior: 'smooth'
      });
    },
    next: function next(page) {
      var _this3 = this;

      this.axios.get(this.$siteUrl + '/get_comment_product/' + this.product_id + '?page=' + page).then(function (response) {
        _this3.pagination_page = response.data.current_page;
        _this3.pagination_total = response.data.last_page;
        _this3.pagination_perPage = response.data.per_page;

        if (response.data.data.length > 0) {
          _this3.comments = response.data.data;
          var comments_body = document.getElementById("comments-body");
          comments_body.scrollIntoView({
            behavior: 'smooth'
          });
        }
      });
    }
  }
});

/***/ }),

/***/ "./modules/comments/resources/js/components/CommentPage.vue":
/*!******************************************************************!*\
  !*** ./modules/comments/resources/js/components/CommentPage.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _CommentPage_vue_vue_type_template_id_34ed548c_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CommentPage.vue?vue&type=template&id=34ed548c&scoped=true& */ "./modules/comments/resources/js/components/CommentPage.vue?vue&type=template&id=34ed548c&scoped=true&");
/* harmony import */ var _CommentPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CommentPage.vue?vue&type=script&lang=js& */ "./modules/comments/resources/js/components/CommentPage.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _CommentPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _CommentPage_vue_vue_type_template_id_34ed548c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _CommentPage_vue_vue_type_template_id_34ed548c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "34ed548c",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "modules/comments/resources/js/components/CommentPage.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./modules/comments/resources/js/components/CommentProduct.vue":
/*!*********************************************************************!*\
  !*** ./modules/comments/resources/js/components/CommentProduct.vue ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _CommentProduct_vue_vue_type_template_id_2ccdab02_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CommentProduct.vue?vue&type=template&id=2ccdab02&scoped=true& */ "./modules/comments/resources/js/components/CommentProduct.vue?vue&type=template&id=2ccdab02&scoped=true&");
/* harmony import */ var _CommentProduct_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CommentProduct.vue?vue&type=script&lang=js& */ "./modules/comments/resources/js/components/CommentProduct.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _CommentProduct_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _CommentProduct_vue_vue_type_template_id_2ccdab02_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _CommentProduct_vue_vue_type_template_id_2ccdab02_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "2ccdab02",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "modules/comments/resources/js/components/CommentProduct.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./modules/comments/resources/js/components/CommentPage.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./modules/comments/resources/js/components/CommentPage.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CommentPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./CommentPage.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./modules/comments/resources/js/components/CommentPage.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CommentPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./modules/comments/resources/js/components/CommentProduct.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************!*\
  !*** ./modules/comments/resources/js/components/CommentProduct.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CommentProduct_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./CommentProduct.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./modules/comments/resources/js/components/CommentProduct.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CommentProduct_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./modules/comments/resources/js/components/CommentPage.vue?vue&type=template&id=34ed548c&scoped=true&":
/*!*************************************************************************************************************!*\
  !*** ./modules/comments/resources/js/components/CommentPage.vue?vue&type=template&id=34ed548c&scoped=true& ***!
  \*************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CommentPage_vue_vue_type_template_id_34ed548c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CommentPage_vue_vue_type_template_id_34ed548c_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CommentPage_vue_vue_type_template_id_34ed548c_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./CommentPage.vue?vue&type=template&id=34ed548c&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./modules/comments/resources/js/components/CommentPage.vue?vue&type=template&id=34ed548c&scoped=true&");


/***/ }),

/***/ "./modules/comments/resources/js/components/CommentProduct.vue?vue&type=template&id=2ccdab02&scoped=true&":
/*!****************************************************************************************************************!*\
  !*** ./modules/comments/resources/js/components/CommentProduct.vue?vue&type=template&id=2ccdab02&scoped=true& ***!
  \****************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CommentProduct_vue_vue_type_template_id_2ccdab02_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CommentProduct_vue_vue_type_template_id_2ccdab02_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CommentProduct_vue_vue_type_template_id_2ccdab02_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./CommentProduct.vue?vue&type=template&id=2ccdab02&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./modules/comments/resources/js/components/CommentProduct.vue?vue&type=template&id=2ccdab02&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./modules/comments/resources/js/components/CommentPage.vue?vue&type=template&id=34ed548c&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./modules/comments/resources/js/components/CommentPage.vue?vue&type=template&id=34ed548c&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "v-container",
    { attrs: { id: "comment_tag" } },
    [
      _vm.pagination_total > 1
        ? _c("v-pagination", {
            attrs: {
              length: _vm.pagination_total,
              "total-visible": 7,
              circle: "",
            },
            on: { input: _vm.next },
            model: {
              value: _vm.pagination_page,
              callback: function ($$v) {
                _vm.pagination_page = $$v
              },
              expression: "pagination_page",
            },
          })
        : _vm._e(),
      _vm._v(" "),
      _vm._l(_vm.comments, function (row, key) {
        return _vm.comments != null
          ? _c("div", [
              _c(
                "div",
                {
                  class: [
                    row.status === 1
                      ? "Accepted comment_box"
                      : "pending_approval comment_box",
                  ],
                },
                [
                  _c("div", { staticClass: "comment_header_box" }, [
                    _c(
                      "div",
                      [
                        row.status === 0
                          ? _c(
                              "v-btn",
                              {
                                staticClass: "white--text",
                                attrs: { color: "green" },
                                on: {
                                  click: function ($event) {
                                    return _vm.set_status(row.id, 1)
                                  },
                                },
                              },
                              [_vm._v("تایید کامنت")]
                            )
                          : row.status === 1
                          ? _c(
                              "v-btn",
                              {
                                staticClass: "white--text",
                                attrs: { color: "red" },
                                on: {
                                  click: function ($event) {
                                    return _vm.set_status(row.id, 0)
                                  },
                                },
                              },
                              [_vm._v("عدم تایید کامنت")]
                            )
                          : _vm._e(),
                        _vm._v(" "),
                        row.status === 1
                          ? _c("span", { staticClass: "mx-16" }, [
                              _vm._v("تایید شده"),
                            ])
                          : _c("span", { staticClass: "mx-16" }, [
                              _vm._v("در انتظار تایید"),
                            ]),
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      [
                        _c("delete-link", {
                          attrs: {
                            "row-id": row.id,
                            label: "کامنت",
                            sendTrash: "yes",
                            url: _vm.$siteUrl + "/admin/comments/" + row.id,
                          },
                        }),
                        _vm._v(" "),
                        _c(
                          "span",
                          {
                            attrs: {
                              "data-toggle": "tooltip",
                              "data-placement": "bottom",
                              title: "حذف نظر برای همیشه",
                              onclick: "del_row(row.id)",
                            },
                          },
                          [_c("v-icon", [_vm._v("mdi-delete-forever")])],
                          1
                        ),
                        _vm._v(" "),
                        _c(
                          "span",
                          {
                            attrs: {
                              "data-toggle": "tooltip",
                              "data-placement": "bottom",
                              title: "بازیابی نظر",
                              onclick: "restore_row(row.id)",
                            },
                          },
                          [_c("v-icon", [_vm._v("mdi-refresh")])],
                          1
                        ),
                      ],
                      1
                    ),
                  ]),
                  _vm._v(" "),
                  _c("div", [
                    _c("div", { staticClass: "title_product" }, [
                      _c("span", [_vm._v("ثبت شده برای : ")]),
                      _vm._v(" "),
                      _c(
                        "a",
                        {
                          attrs: {
                            href:
                              _vm.$siteUrl +
                              "/product/" +
                              row["product"]["slug"],
                          },
                        },
                        [_vm._v(_vm._s(row["product"]["title"]))]
                      ),
                    ]),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "comment_content mx-4" },
                      [
                        _c("nl2br", { attrs: { tag: "p", text: row.content } }),
                        _vm._v(" "),
                        _c("div", { staticClass: "time_reg" }, [
                          _c("div", { staticClass: "time_reg" }, [
                            _c("span", [_vm._v("ثبت شده توسط")]),
                            _vm._v(" "),
                            row.user_id > 0
                              ? _c("span", [_vm._v(_vm._s(row.name))])
                              : _c("span", [_vm._v("کاربر مهمان")]),
                            _vm._v(" "),
                            _c("span", [_vm._v("در تاریخ")]),
                            _vm._v(" "),
                            _c("span", [
                              _vm._v(
                                _vm._s(
                                  _vm._f("moment")(
                                    row.created_at,
                                    "jDD-jMM-jYYYY  |  h:mm:ss"
                                  )
                                )
                              ),
                            ]),
                          ]),
                        ]),
                      ],
                      1
                    ),
                    _vm._v(" "),
                    row["get_parent"]
                      ? _c("span", { staticClass: "mx-6" }, [
                          _vm._v("در پاسخ برای پیام :"),
                        ])
                      : _vm._e(),
                    _vm._v(" "),
                    row["get_parent"]
                      ? _c(
                          "div",
                          { staticClass: "comment_content mx-10" },
                          [
                            _c("nl2br", {
                              attrs: {
                                tag: "p",
                                text: row["get_parent"]["content"],
                              },
                            }),
                            _vm._v(" "),
                            _c("div", { staticClass: "time_reg" }, [
                              _c("div", { staticClass: "time_reg" }, [
                                _c("span", [_vm._v("ثبت شده توسط")]),
                                _vm._v(" "),
                                row.get_parent.user_id > 0
                                  ? _c("span", [
                                      _vm._v(_vm._s(row.get_parent.name)),
                                    ])
                                  : _c("span", [_vm._v("کاربر مهمان")]),
                                _vm._v(" "),
                                _c("span", [_vm._v("در تاریخ")]),
                                _vm._v(" "),
                                _c("span", [
                                  _vm._v(
                                    _vm._s(
                                      _vm._f("moment")(
                                        row.get_parent.created_at,
                                        "jDD-jMM-jYYYY  |  h:mm:ss"
                                      )
                                    )
                                  ),
                                ]),
                              ]),
                            ]),
                          ],
                          1
                        )
                      : _vm._e(),
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "mx-4 mb-4" },
                    [
                      _c(
                        "v-btn",
                        {
                          attrs: { color: "primary" },
                          on: {
                            click: function ($event) {
                              return _vm.add_answer(row)
                            },
                          },
                        },
                        [_vm._v("ثبت پاسخ")]
                      ),
                    ],
                    1
                  ),
                ]
              ),
            ])
          : _c(
              "p",
              {
                staticStyle: {
                  "padding-top": "30px",
                  "padding-bottom": "20px",
                  "text-align": "center",
                },
              },
              [_vm._v("رکوردی برای نمایش یافت نشد")]
            )
      }),
      _vm._v(" "),
      _vm.pagination_total > 1
        ? _c("v-pagination", {
            attrs: {
              length: _vm.pagination_total,
              "total-visible": 7,
              circle: "",
            },
            on: { input: _vm.next },
            model: {
              value: _vm.pagination_page,
              callback: function ($$v) {
                _vm.pagination_page = $$v
              },
              expression: "pagination_page",
            },
          })
        : _vm._e(),
      _vm._v(" "),
      _c(
        "v-dialog",
        {
          attrs: { persistent: "", "max-width": "600px" },
          model: {
            value: _vm.dialog,
            callback: function ($$v) {
              _vm.dialog = $$v
            },
            expression: "dialog",
          },
        },
        [
          _c(
            "v-card",
            [
              _c("v-card-title", [_c("span", [_vm._v("پاسخ پیام")])]),
              _vm._v(" "),
              _c(
                "v-card-text",
                [
                  _c(
                    "v-container",
                    [
                      _c(
                        "div",
                        {
                          staticClass:
                            "bg-yellow-100 text-yellow-600 border-yellow-500 rounded pa-4 font-size-14 my-4",
                        },
                        [
                          _c("ul", { staticClass: "list-decimal pr-4" }, [
                            _c("li", [
                              _vm._v(
                                "ارسال پاسخ به پیام : " + _vm._s(_vm.reply_to)
                              ),
                            ]),
                          ]),
                          _vm._v(" "),
                          _c(
                            "div",
                            { staticClass: "content-area align-justify my-4" },
                            [
                              _vm.reply_content
                                ? _c("nl2br", {
                                    attrs: {
                                      tag: "p",
                                      text: _vm.reply_content,
                                    },
                                  })
                                : _vm._e(),
                            ],
                            1
                          ),
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "v-form",
                        { ref: "form" },
                        [
                          _c("v-textarea", {
                            attrs: {
                              id: "content",
                              name: "content",
                              label: "متن پیام",
                              rows: "8",
                              rules: _vm.contentRules,
                              counter: "",
                              outlined: "",
                              required: "",
                            },
                            model: {
                              value: _vm.content,
                              callback: function ($$v) {
                                _vm.content = $$v
                              },
                              expression: "content",
                            },
                          }),
                        ],
                        1
                      ),
                    ],
                    1
                  ),
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "v-card-actions",
                [
                  _c("v-spacer"),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: { color: "red darken-1", outlined: "" },
                      on: {
                        click: function ($event) {
                          _vm.dialog = false
                        },
                      },
                    },
                    [_vm._v("\n                    انصراف\n                ")]
                  ),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: { color: "blue darken-1", outlined: "" },
                      on: { click: _vm.send_comment },
                    },
                    [
                      _vm._v(
                        "\n                    ثبت دیدگاه\n                "
                      ),
                    ]
                  ),
                ],
                1
              ),
            ],
            1
          ),
        ],
        1
      ),
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./modules/comments/resources/js/components/CommentProduct.vue?vue&type=template&id=2ccdab02&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./modules/comments/resources/js/components/CommentProduct.vue?vue&type=template&id=2ccdab02&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "my-5", attrs: { id: "comments-body" } },
    [
      _c(
        "div",
        { staticClass: "d-flex justify-between mb-5 align-center" },
        [
          _c("h3", { staticClass: "text-gray-600" }, [
            _vm._v("دیدگاه‌ها و پرسش‌ها"),
          ]),
          _vm._v(" "),
          _c(
            "v-btn",
            {
              staticClass: "white--text pa-2 rounded-lg cursor-pointer",
              attrs: { color: "rgba(71,85,105,1)" },
              on: {
                click: function ($event) {
                  return _vm.show_send_comment()
                },
              },
            },
            [_vm._v("ارسال دیدگاه")]
          ),
        ],
        1
      ),
      _vm._v(" "),
      _vm.comment_box
        ? _c(
            "div",
            {
              staticClass:
                "border-p bg-white rounded-lg padding-0 box_shadow_product pa-6 mb-5",
            },
            [
              _c("div", { staticClass: "d-flex align-center" }, [
                _c("img", {
                  staticClass:
                    "w-20 h-20 ml-5 rounded-circle border_img bg-gray-300",
                  attrs: {
                    src:
                      _vm.auth && _vm.auth["pic_profile"]
                        ? _vm.$siteUrl +
                          "/files/upload/" +
                          _vm.auth["pic_profile"]
                        : "/files/icons/default_user.png",
                    alt: "عکس نمایه شخصی",
                  },
                }),
                _vm._v(" "),
                _c("div", [
                  _vm.auth && _vm.auth["name"]
                    ? _c("span", { staticClass: "bold" }, [
                        _vm._v(_vm._s(_vm.auth["name"])),
                      ])
                    : _c("span", { staticClass: "bold" }, [
                        _vm._v("کاربر مهمان"),
                      ]),
                  _vm._v(" "),
                  _vm.auth && _vm.auth["role"] === "admin"
                    ? _c("span", { staticClass: "d-block font-size-12" }, [
                        _vm._v("مدیر سایت"),
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _c("span", { staticClass: "d-block font-size-12 mt-1" }, [
                    _vm._v("ارسال دیدگاه"),
                  ]),
                ]),
              ]),
              _vm._v(" "),
              _vm._m(0),
              _vm._v(" "),
              _vm.reply
                ? _c(
                    "div",
                    {
                      staticClass:
                        "bg-yellow-100 text-yellow-600 border-yellow-500 rounded pa-4 font-size-14 mt-4",
                    },
                    [
                      _c("ul", { staticClass: "list-decimal pr-4" }, [
                        _c("li", [
                          _vm._v(
                            "ارسال پاسخ به پیام : " + _vm._s(_vm.reply_to)
                          ),
                        ]),
                      ]),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "content-area align-justify my-4" },
                        [
                          _c("nl2br", {
                            attrs: { tag: "p", text: _vm.reply_content },
                          }),
                        ],
                        1
                      ),
                    ]
                  )
                : _vm._e(),
              _vm._v(" "),
              _c("v-form", { ref: "form" }, [
                _c(
                  "div",
                  { staticClass: "d-flex justify-lg-space-around mt-4" },
                  [
                    _vm.auth
                      ? _c("v-text-field", {
                          staticClass: "col-12 col-lg-5 col-md-5",
                          attrs: {
                            id: "name",
                            name: "name",
                            label: "نام و نام خانوادگی",
                            outlined: "",
                            required: "",
                            value: _vm.auth["name"],
                            disabled: "",
                          },
                        })
                      : _c("v-text-field", {
                          staticClass: "col-12 col-lg-5 col-md-5",
                          attrs: {
                            id: "name",
                            name: "name",
                            rules: _vm.nameRules,
                            label: "نام و نام خانوادگی",
                            outlined: "",
                            required: "",
                          },
                          model: {
                            value: _vm.name,
                            callback: function ($$v) {
                              _vm.name = $$v
                            },
                            expression: "name",
                          },
                        }),
                    _vm._v(" "),
                    _vm.auth
                      ? _c("v-text-field", {
                          staticClass: "col-12 col-lg-5 col-md-5",
                          attrs: {
                            id: "email",
                            dir: "ltr",
                            name: "email",
                            label: "ایمیل",
                            outlined: "",
                            required: "",
                            value: _vm.auth["email"],
                            disabled: "",
                          },
                        })
                      : _c("v-text-field", {
                          staticClass: "col-12 col-lg-5 col-md-5",
                          attrs: {
                            id: "email",
                            dir: "ltr",
                            name: "email",
                            rules: _vm.emailRules,
                            label: "ایمیل",
                            outlined: "",
                            required: "",
                          },
                          model: {
                            value: _vm.email,
                            callback: function ($$v) {
                              _vm.email = $$v
                            },
                            expression: "email",
                          },
                        }),
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "col-12" },
                  [
                    _c("v-textarea", {
                      attrs: {
                        id: "content",
                        name: "content",
                        label: "متن پیام",
                        rows: "8",
                        rules: _vm.contentRules,
                        counter: "",
                        outlined: "",
                        required: "",
                      },
                      model: {
                        value: _vm.content,
                        callback: function ($$v) {
                          _vm.content = $$v
                        },
                        expression: "content",
                      },
                    }),
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "d-flex justify-end pt-4" },
                  [
                    _c(
                      "v-btn",
                      {
                        staticClass: "pa-2 mx-2 rounded-lg cursor-pointer",
                        attrs: { color: "primary" },
                        on: { click: _vm.send_comment },
                      },
                      [_vm._v("ثبت دیدگاه")]
                    ),
                    _vm._v(" "),
                    _c(
                      "v-btn",
                      {
                        staticClass:
                          "white--text pa-2 mx-2 rounded-lg cursor-pointer",
                        attrs: { color: "red" },
                        on: {
                          click: function ($event) {
                            _vm.comment_box = false
                          },
                        },
                      },
                      [_vm._v("انصراف")]
                    ),
                  ],
                  1
                ),
              ]),
            ],
            1
          )
        : _vm._e(),
      _vm._v(" "),
      _vm._l(_vm.comments, function (row, key) {
        return _vm.comments != null
          ? _c(
              "div",
              {
                staticClass:
                  "border-p bg-white rounded-lg padding-0 box_shadow_product pa-6 mb-5",
              },
              [
                _c("div", { staticClass: "d-flex justify-between" }, [
                  _c("div", { staticClass: "d-flex align-center" }, [
                    _c("img", {
                      staticClass:
                        "w-20 h-20 ml-5 rounded-circle border_img bg-gray-300",
                      attrs: {
                        src:
                          row["get_user_info"] &&
                          row["get_user_info"]["pic_profile"]
                            ? _vm.$siteUrl +
                              "/files/upload/" +
                              row["get_user_info"]["pic_profile"]
                            : _vm.$siteUrl + "/files/icons/default_user.png",
                        alt: "عکس نمایه شخصی1111",
                      },
                    }),
                    _vm._v(" "),
                    _c("div", [
                      row["get_user_info"]
                        ? _c("span", { staticClass: "bold" }, [
                            _vm._v(_vm._s(row["get_user_info"]["name"])),
                          ])
                        : _c("span", { staticClass: "bold" }, [
                            _vm._v(_vm._s(row.name)),
                          ]),
                      _vm._v(" "),
                      row["get_user_info"] === null
                        ? _c("span", { staticClass: "d-block font-size-12" }, [
                            _vm._v("کاربر مهمان"),
                          ])
                        : row["get_user_info"]["role"] === "admin"
                        ? _c("span", { staticClass: "d-block font-size-12" }, [
                            _vm._v("مدیر سایت"),
                          ])
                        : _vm._e(),
                      _vm._v(" "),
                      _c("span", { staticClass: "d-block font-size-12 mt-1" }, [
                        _vm._v(
                          _vm._s(
                            _vm._f("moment")(row.created_at, "from", "now")
                          )
                        ),
                      ]),
                    ]),
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "d-flex h-fit-content" },
                    [
                      _c(
                        "v-icon",
                        {
                          on: {
                            click: function ($event) {
                              return _vm.add_answer(row)
                            },
                          },
                        },
                        [_vm._v("mdi-arrow-left")]
                      ),
                    ],
                    1
                  ),
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "content-area align-justify my-5 " },
                  [_c("nl2br", { attrs: { tag: "p", text: row.content } })],
                  1
                ),
                _vm._v(" "),
                _vm._l(row["get_child"], function (row1, key1) {
                  return row["get_child"]
                    ? _c(
                        "div",
                        {
                          staticClass:
                            "border-p bg-gray-50 rounded-lg padding-0 box_shadow_product pa-6 mb-5",
                        },
                        [
                          _c("div", { staticClass: "d-flex justify-between" }, [
                            _c("div", { staticClass: "d-flex align-center" }, [
                              _c("img", {
                                staticClass:
                                  "w-20 h-20 ml-5 rounded-circle border_img bg-gray-300",
                                attrs: {
                                  src:
                                    row1["get_user_info"] &&
                                    row1["get_user_info"]["pic_profile"]
                                      ? _vm.$siteUrl +
                                        "/files/upload/" +
                                        row1["get_user_info"]["pic_profile"]
                                      : "/files/icons/default_user.png",
                                  alt: "عکس نمایه شخصی",
                                },
                              }),
                              _vm._v(" "),
                              _c("div", [
                                row1["get_user_info"]
                                  ? _c("span", { staticClass: "bold" }, [
                                      _vm._v(
                                        _vm._s(row1["get_user_info"]["name"])
                                      ),
                                    ])
                                  : _c("span", { staticClass: "bold" }, [
                                      _vm._v(_vm._s(row1.name)),
                                    ]),
                                _vm._v(" "),
                                row1["get_user_info"] === null
                                  ? _c(
                                      "span",
                                      { staticClass: "d-block font-size-12" },
                                      [_vm._v("کاربر مهمان")]
                                    )
                                  : row1["get_user_info"]["role"] === "admin"
                                  ? _c(
                                      "span",
                                      { staticClass: "d-block font-size-12" },
                                      [_vm._v("مدیر سایت")]
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                _c(
                                  "span",
                                  { staticClass: "d-block font-size-12 mt-1" },
                                  [
                                    _vm._v(
                                      _vm._s(
                                        _vm._f("moment")(
                                          row1.created_at,
                                          "from",
                                          "now"
                                        )
                                      )
                                    ),
                                  ]
                                ),
                              ]),
                            ]),
                          ]),
                          _vm._v(" "),
                          _c(
                            "div",
                            { staticClass: "content-area align-justify my-5 " },
                            [
                              _c("nl2br", {
                                attrs: { tag: "p", text: row1.content },
                              }),
                            ],
                            1
                          ),
                        ]
                      )
                    : _vm._e()
                }),
              ],
              2
            )
          : _vm._e()
      }),
      _vm._v(" "),
      _vm.pagination_total > 1
        ? _c("v-pagination", {
            attrs: {
              length: _vm.pagination_total,
              "total-visible": 7,
              circle: "",
            },
            on: { input: _vm.next },
            model: {
              value: _vm.pagination_page,
              callback: function ($$v) {
                _vm.pagination_page = $$v
              },
              expression: "pagination_page",
            },
          })
        : _vm._e(),
    ],
    2
  )
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass:
          "bg-lightBlue-100 text-lightBlue-600 border-lightBlue-300 rounded pa-4 d-flex align-center font-size-12 mt-4",
      },
      [
        _c("ul", { staticClass: "list-decimal pr-4" }, [
          _c("li", [
            _vm._v("دیدگاه شما بعد از "),
            _c("strong", [_vm._v("تایید")]),
            _vm._v(" در قسمت نظرات نمایش داده خواهد شد."),
          ]),
        ]),
      ]
    )
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ normalizeComponent)
/* harmony export */ });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ })

}]);