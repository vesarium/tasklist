import 'core-js/stable'
import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios'
import Repository from "./repositories/Repository";
import VueAxios from 'vue-axios'
import VueAuth from '@websanova/vue-auth'
import auth from './auth'
import CoreuiVue from '@coreui/vue'
import { iconsSet as icons } from './assets/icons/icons.js'
import store from './store'
import Vuelidate from 'vuelidate';

Vue.config.performance = true;
Vue.use(CoreuiVue);
Vue.use(VueAxios, axios);
Vue.use(VueAuth, auth);
//Vue.prototype.$log = console.log.bind(console);
Vue.use(Vuelidate);

//axios.defaults.baseURL = 'http://tasklist-api.local:8081/api';

new Vue({
  el: '#app',
  router,
  axios,
  Repository,
  store,
  icons,
  template: '<App/>',
  components: {
    App
  }
});

