window.Vue = require('vue');

require('./bootstrap');
import BootstrapVue from 'bootstrap-vue';
Vue.use(BootstrapVue);
Vue.use(VueRouter);

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
Vue.use(VueIziToast);


import { library } from '@fortawesome/fontawesome-svg-core'
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
library.add(faUserSecret)
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.config.productionTip = false

import Vue from 'vue';
import LightGallery from 'vue-light-gallery';
 
Vue.use(LightGallery);
 
new Vue(/* ... */);

import { mdbvue} from 'mdbvue';

import home from './components/Home.vue';
import newAidVisual from './components/NewAidVisual.vue';
import editAidVisual from './components/EditAidVisual.vue';
import homeAdmin from './components/HomeAdmin.vue';

/*
Vue.component('home', require('./components/home.vue').default);
Vue.component('new-aid-visual', require('./components/newAidVisual.vue').default);
*/
import VueRouter from 'vue-router';

const routes = [  
    { path: '/', component: home },
    { path: '/admin/db09cd1b35b31c5f6a9aa2541f27ae75', component: homeAdmin },
    { path: '/new-aid-visual', component: newAidVisual },
    { path: '/edit-aid-visual/:id', name:"edit-aid-visual", component: editAidVisual },
  ];
  
  const router = new VueRouter({
    routes // short for `routes: routes`
});

const app = new Vue({
   el:'#app',
   router
});
