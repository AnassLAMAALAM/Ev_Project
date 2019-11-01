window.Vue = require('vue');

require('./bootstrap');
import BootstrapVue from 'bootstrap-vue';
Vue.use(BootstrapVue);

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
Vue.use(VueIziToast);


import Vue from 'vue';
import LightGallery from 'vue-light-gallery';
 
Vue.use(LightGallery);
 
new Vue(/* ... */);



import { mdbvue} from 'mdbvue';

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('add-ev', require('./components/addEv.vue').default);




const app = new Vue({
    el: '#app'
});
