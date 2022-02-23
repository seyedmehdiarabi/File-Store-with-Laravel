import Vue from "vue";
window.Vue = Vue;
import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueAxios, axios);

import {store} from "./store";
window.store = store;

Vue.prototype.$siteUrl = document.querySelector('meta[name="app_url"]').getAttribute('content');
Vue.prototype.$siteName = 'communication';
Vue.prototype.$productUrlParam = '/';

Vue.component('app-content', require('./components/AppContent').default);
//Vue.component('progress-box', require('./components/Progress').default);

import Vuetify from 'vuetify';
Vue.use(Vuetify);
window.Vuetify = Vuetify;

import(/* webpackChunkName:'users' */'../../modules/users/resources/js/components.js');
import(/* webpackChunkName:'theme1' */'../../modules/theme1/resources/js/components.js');
import(/* webpackChunkName:'panelTheme' */'../../modules/panelTheme/resources/js/components.js');
import(/* webpackChunkName:'visitStatistics' */'../../modules/visitStatistics/resources/js/components.js');
