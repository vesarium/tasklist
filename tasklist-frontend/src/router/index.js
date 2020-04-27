import Vue from 'vue'
import Router from 'vue-router'
import store from "../store";
import { Role } from '../_helpers/role';

const ifNotAuthenticated = (to, from, next) => {
  if (!store.getters.isAuthenticated) {
    next();
    return;
  }
  next("/dashboard");
};

const ifAuthenticated = (to, from, next) => {
  if (store.getters.isAuthenticated) {
    next();
    return;
  }
  next("/login");
};

// Containers
const TheContainer = () => import('@/containers/TheContainer');

// Views
const Login = () => import('@/views/Login');
const Page404 = () => import('@/views/Page404');
const Page500 = () => import('@/views/Page500');
const Dashboard = () => import('@/views/Dashboard');
// const Users = () => import('@/views/Users');
const Users = () => import('@/views/users/Users');
const UserProfile = () => import('@/views/users/UserProfile');
const Tasks = () => import('@/views/tasks/Tasks');
const ManageRespectPoints = () => import('@/views/manage-respect-points/ManageRespectPoints');

Vue.use(Router);

const router = Vue.router = new Router({
  //mode: 'hash',
  mode:'history',
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
      beforeEnter: ifNotAuthenticated,
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: '/login',
          name: 'Login',
          component: Login,
          meta: {
            auth: false
          },
        },
      ]
    },
    {
      path: '/dashboard',
      name: 'Home',
      component: TheContainer,
      beforeEnter: ifAuthenticated,
      children: [
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard,
          meta: {
            auth: true
          },
          beforeEnter: ifAuthenticated,
        },
        // {
        //   path: '/us',
        //   name: 'Tables',
        //   component: Tables
        // },
      ]
    },
    {
      path: '/users',
      name: 'Users',
      component: TheContainer,
      beforeEnter: ifAuthenticated,
      children: [
        {
          path: '',
          name: 'Users',
          component: Users,
          meta: {
            auth: false,
            authorize: [Role.ROLE_ADMIN]
          },
          beforeEnter: ifAuthenticated,
        },
        {
          path: 'profile',
          name: 'UserProfile',
          component: UserProfile,
          meta: {
            auth: false,
            authorize: [Role.ROLE_USER, Role.ROLE_ADMIN]
          },
          beforeEnter: ifAuthenticated,
        }
      ]
    },
    {
      path: '/my-tasks',
      name: 'Tasks',
      component: TheContainer,
      beforeEnter: ifAuthenticated,
      children: [
        {
          path: '',
          name: 'Tasks',
          component: Tasks,
          meta: {
            auth: false,
            authorize: [Role.ROLE_ADMIN, Role.ROLE_USER]
          },
          beforeEnter: ifAuthenticated,
        }
      ]
    }, 
    {
      path: '/tasks',
      name: 'Tasks',
      component: TheContainer,
      beforeEnter: ifAuthenticated,
      children: [
        {
          path: '',
          name: 'Tasks',
          component: Tasks,
          meta: {
            auth: false,
            authorize: [Role.ROLE_ADMIN, Role.ROLE_USER]
          },
          beforeEnter: ifAuthenticated,
        }
      ]
    },
    {
      path: '/manage-respect-points',
      name: 'ManageRespectPoints',
      component: TheContainer,
      beforeEnter: ifAuthenticated,
      children: [
        {
          path: '',
          name: 'ManageRespectPoints',
          component: ManageRespectPoints,
          meta: {
            auth: false,
            authorize: [Role.ROLE_USER]
          },
          beforeEnter: ifAuthenticated,
        }
      ]
    },
    {
      path: '*',
      name: 'Page404',
      component: Page404,
      meta: {
        auth: false
      }
    },
    {
      path: '/500',
      name: 'Page500',
      component: Page500,
      meta: {
        auth: false
      },
    }
  ]
}


Vue.router.beforeEach((to, from, next) => {
  const { authorize } = to.meta;
  const currentUser = store.getters.role;
  if (authorize) {
    if (store.getters.isAuthenticated) {
      if (authorize.length && !authorize.includes(currentUser))
        return next({ path: '/500'});
    } else { return next({ path: '/dashboard'}); }
  }
  next();
})

export default Vue.router
