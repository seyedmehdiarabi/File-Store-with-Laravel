import Vue from "vue";
window.Vue = Vue;
import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueAxios, axios);
import {store} from "./store";
window.store = store;
import(/* webpackChunkName:'users' */'../../modules/users/resources/js/components.js');
import(/* webpackChunkName:'theme1' */'../../modules/theme1/resources/js/components.js');
import(/* webpackChunkName:'panelTheme' */'../../modules/panelTheme/resources/js/components.js');
import(/* webpackChunkName:'visitStatistics' */'../../modules/visitStatistics/resources/js/components.js');
import(/* webpackChunkName:'cart' */'../../modules/cart/resources/js/components.js');
import(/* webpackChunkName:'discount' */'../../modules/discount/resources/js/components.js');
import(/* webpackChunkName:'orders' */'../../modules/orders/resources/js/components.js');
import(/* webpackChunkName:'shop' */'../../modules/shop/resources/js/components.js');
import(/* webpackChunkName:'comments' */'../../modules/comments/resources/js/components.js');





import VuePersianDatetimePicker from 'vue-persian-datetime-picker';

Vue.use(VuePersianDatetimePicker, {
    name: 'date-picker',
    props: {
        inputFormat: 'YYYY/MM/DD',
        format: 'jYYYY/jMM/jDD',
        editable: false,
        inputClass: 'form-control persian_number',
        altFormat: 'YYYY/MM/DD',
        color: '#5c6bc0',
        autoSubmit: false,
        locale: "fa,en",
        //popover: "bottom-left"
    }
});

