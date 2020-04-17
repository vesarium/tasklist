import Vue from 'vue'
import Router from 'vue-router'

// Containers
const TheContainer = () => import('@/containers/TheContainer');

// Views
const Login = () => import('@/views/Login');
const Page404 = () => import('@/views/Page404');
const Page500 = () => import('@/views/Page500');
const Dashboard = () => import('@/views/Dashboard');

Vue.use(Router);

Vue.router = new Router({
  mode: 'hash', // https://router.vuejs.org/api/#mode
  linkActiveClass: 'active',
  scrollBehavior: () => ({ y: 0 }),
  routes: configRoutes()
});

function configRoutes () {
  return [
    {
      path: '/',
      redirect: '/login',
      name: 'Login',
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: 'login',
          name: 'Login',
          component: Login,
          meta: {
            auth: false
          }
        },
        {
          path: '404',
          name: 'Page404',
          component: Page404,
          meta: {
            auth: false
          }
        },
        {
          path: '500',
          name: 'Page500',
          component: Page500,
          meta: {
            auth: false
          }
        }
      ]
    },
    {
      path: '/dashboard',
      name: 'Home',
      component: TheContainer,
      children: [
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard,
          meta: {
            auth: true
          }
        }
      ]
    }
  ]
}

export default Vue.router
