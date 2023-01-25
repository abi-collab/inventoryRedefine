
require('./bootstrap');

// window.Vue = require('vue'); //or bellow line
import Vue from 'vue'      //--these nxt 3_lines are newly added as we installed vue-router
import VueRouter from 'vue-router'
Vue.use(VueRouter)

Vue.use(require('vue-moment'));


//----route imported-------
import {routes} from './routes';

// import {store} from './store/index';

import VueHtmlToPaper from 'vue-html-to-paper';

import JsonExcel from "vue-json-excel";
 
Vue.component("downloadExcel", JsonExcel);

import 'vue-search-select/dist/VueSearchSelect.css';

import VueApexCharts from 'vue-apexcharts'
Vue.use(VueApexCharts)

Vue.component('apexchart', VueApexCharts)

const options = {
  name: '_blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
  styles: [
    'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
    'https://unpkg.com/kidlat-css/css/kidlat.css'
  ],
  timeout: 1000, // default timeout before the print window appears
  autoClose: true, // if false, the window will not close after printing
  windowTitle: window.document.title, // override the window title
}

Vue.use(VueHtmlToPaper, options);

// or, using the defaults with no stylesheet
Vue.use(VueHtmlToPaper);

//---import User Class----
import User from './helpers/User';
window.User = User                  //--for 'globally' use


//----import Notification Class-------
import Notification from './helpers/Notification';  //--for 'globally' use
window.Notification = Notification


//----start-----Sweetalert2------
import Swal from 'sweetalert2'
window.Swal = Swal              //--for 'globally' use

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

window.Toast = Toast;       //--for 'globally' use
//----End-----Sweetalert2------------//------

//------Reload-----
window.Reload = new Vue();   //--for reload() method /to instantly reload page


const router = new VueRouter({  //------------
    routes,
    mode: 'history'     //To remove '#' from URL
})

const app = new Vue({
  router,         //-----------
    // store,
    el: '#app',
  
});
