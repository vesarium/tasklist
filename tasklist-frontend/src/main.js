import 'core-js/stable'
import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VueAuth from '@websanova/vue-auth'
import auth from './auth'
import CoreuiVue from '@coreui/vue'
import { iconsSet as icons } from './assets/icons/icons.js'
import store from './store'

Vue.config.performance = true;
Vue.use(CoreuiVue);
Vue.use(VueAxios, axios);
Vue.use(VueAuth, auth);
Vue.prototype.$log = console.log.bind(console);

axios.defaults.baseURL = 'http://tasklist-api.local/api';
axios.defaults.headers.common = {
  'Authorization': 'Bearer ' + localStorage.getItem('token'),
  'Content-type': 'application/x-www-form-urlencoded'
};

new Vue({
  el: '#app',
  router,
  axios,
  store,
  icons,
  template: '<App/>',
  components: {
    App
  }
});

