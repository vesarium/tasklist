// Auth base configuration some of this options
import bearer from '@websanova/vue-auth/drivers/auth/bearer'
import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'

// can be override in method calls
const config = {
  auth: bearer,
  http: axios,
  router: router,
  tokenDefaultName: 'symfony-vue-app',
  tokenStore: ['localStorage'],
  rolesVar: 'role',
  loginData: {url: '/login', method: 'POST', redirect: '/dashboard', fetchUser: true},
  logoutData: {url: '/logout', method: 'POST', redirect: '/', makeRequest: true},
  fetchData: {url: '/user', method: 'GET', enabled: true},
  refreshData: {url: '/refresh', method: 'GET', enabled: true, interval: 30}
};

export default config
