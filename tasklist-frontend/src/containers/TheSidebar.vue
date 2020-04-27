<template>
  <CSidebar 
    fixed 
    :minimize="minimize"
    :show="show"
    @update:show="(value) => $store.commit('set', ['sidebarShow', value])"
  >
    <CSidebarBrand class="d-md-down-none" to="/">
      <!-- <CIcon 
        class="c-sidebar-brand-full" 
        name="logo" 
        size="custom-size" 
        :height="35" 
        viewBox="0 0 556 134"
      />
      <CIcon 
        class="c-sidebar-brand-minimized" 
        name="logo" 
        size="custom-size" 
        :height="35" 
        viewBox="0 0 110 134"
      /> -->
      <h3 v-if="isAdmin">Admin Panel</h3>
      <h3 v-if="isUser"> {{isUserCount}} </h3>
    </CSidebarBrand>

    <!-- <CRenderFunction flat :content-to-render="$options.nav"/> -->
    <CSidebarNav>
      <CSidebarNavItem name= 'Dashboard' to= '/dashboard'
        icon= 'cil-speedometer' :badge= "{'color': 'primary','text': 'NEW'}" >
        
      </CSidebarNavItem>
      <CSidebarNavItem v-if="isAdmin" name= 'Manage Users' to= '/users'
        icon= 'cil-people' />
      <CSidebarNavItem v-if="isAdmin || isUser" :name="(isAdmin)?'Manage Tasks':'Tasks'" to= '/tasks'
        icon= 'cil-task' />
      <CSidebarNavItem v-if="isAdmin || isUser" name= 'My Tasks' to= '/my-tasks'
        icon= 'cil-task' />
      <CSidebarNavItem v-if="isUser" name= 'Manage Respect Points' to= '/manage-respect-points'
        icon= 'cil-task' />
      <CSidebarNavItem v-if="isUser" name= 'Exchange Respect Points' to= '/'
        icon= 'cil-task' />
        
    </CSidebarNav>
    <CSidebarMinimizer
      class="d-md-down-none"
      @click.native="$store.commit('set', ['sidebarMinimize', !minimize])"
    />
  </CSidebar>
</template>

<script>
import nav from './_nav'
import store from "../store";
import { Role } from '../_helpers/role';

export default {
  name: 'TheSidebar',
  nav,
  data () {
    return {
      username : ''
    }
  },
  computed: {
    show () {
      return this.$store.state.sidebarShow 
    },
    minimize () {
      return this.$store.state.sidebarMinimize 
    },
    isAdmin () {
      return store.getters.isAuthenticated && store.getters.role === Role.ROLE_ADMIN;
    },
    isUser () {
      return store.getters.isAuthenticated && store.getters.role === Role.ROLE_USER;
    },
    isUserCount () {
      let username = "";
      username = (store.getters.userData && store.getters.userData.name) ? store.getters.userData.name : 'User Panel';
      username += (store.getters.userData && store.getters.userData.respectPoint) ? ' ('+store.getters.userData.respectPoint+')' : ' (0)';
      return username;
    },
  },
}
</script>
