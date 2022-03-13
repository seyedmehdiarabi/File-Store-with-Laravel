import Vue from "vue";
window.Vue = Vue;
import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueAxios, axios);

import {store} from "./store";
window.store = store;

Vue.prototype.$siteUrl = document.querySelector('meta[name="app_url"]').getAttribute('content');
Vue.prototype.$siteName = 'sellfiles';
Vue.prototype.$productUrlParam = '/';

Vue.component('app-content', require('./components/AppContent').default);
Vue.component('progress-box', require('./components/ProgressBox').default);


Vue.use(require('vue-jalali-moment'));
const moment = require('moment');
moment.locale("fa");

import Nl2br from 'vue-nl2br'
Vue.component('nl2br', Nl2br);

import Vuetify from 'vuetify';
Vue.use(Vuetify);
window.Vuetify = Vuetify;



import(/* webpackChunkName:'users' */'../../modules/users/resources/js/components.js');
import(/* webpackChunkName:'theme1' */'../../modules/theme1/resources/js/components.js');
import(/* webpackChunkName:'panelTheme' */'../../modules/panelTheme/resources/js/components.js');
import(/* webpackChunkName:'visitStatistics' */'../../modules/visitStatistics/resources/js/components.js');
import(/* webpackChunkName:'cart' */'../../modules/cart/resources/js/components.js');
import(/* webpackChunkName:'discount' */'../../modules/discount/resources/js/components.js');
import(/* webpackChunkName:'orders' */'../../modules/orders/resources/js/components.js');
import(/* webpackChunkName:'shop' */'../../modules/shop/resources/js/components.js');
import(/* webpackChunkName:'comments' */'../../modules/comments/resources/js/components.js');


