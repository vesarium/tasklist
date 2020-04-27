<template>
<div class="wrapper">
  <CCard>
    <CCardHeader>Users

      <div class="card-header-actions">
        <CButton  size="sm" class="pull-right" v-on:click="openAddEditModal()" color="success">Add User</CButton>    
      </div>
    </CCardHeader>
      <CCardBody>
        <CDataTable
          :items="items"
          :fields="fields"
          table-filter
          :loading = "isLoading"
          items-per-page-select
          :items-per-page="5"
          :active-page="activePage"
          @page-change="pageChange"
          hover
          sorter
          pagination
          :noItemsView="noItemsView"
        >
      
          <template #show_details="{item}">
            <td class="py-2">
              <CButton
                color="primary"
                square
                size="sm"
                @click="openAddEditModal(item.id)"
              >
                Edit
              </CButton> 
              <CButton
                color="danger"
                class="ml-2"
                square
                size="sm"
                @click="ShowDeleteModal(item.id)"
              > Delete </CButton>
            </td>
          </template>
        </CDataTable>
      </CCardBody>
    </CCard>

    <!-- Delete Model -->
    <CModal :title="deleteModal.title" :closeOnBackdrop="deleteModal.closeOnBackdrop"  :show.sync="deleteModal.status">
      {{ deleteModal.description }}
      <template #footer>
        <CButton v-on:click="deleteRecord" color="danger"> Delete</CButton>
        <CButton v-on:click="deleteModal.status = false" color="secondary">Cancel</CButton>
      </template>
    </CModal>
    <!-- End delete Model -->

    <!-- Add/Edit/Update Model -->
    <CModal :title="(user.id == '') ? 'Add User' : 'Edit User'" :closeOnBackdrop="addEditModal.closeOnBackdrop"  :show.sync="addEditModal.status">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-12">
            <form @submit.prevent="onSubmit">
              <input type="hidden" v-model="user.id" id="id" name="id"/>
              <div class="form-group form-row">
                  <label class="col-sm-3" for="name">Username</label>
                  <div class="col-sm-9">
                    <input type="text" v-model="user.name" id="name" name="name" class="form-control" :class="{ 'is-invalid': submitted && $v.user.name.$error }" />
                    <div v-if="submitted && !$v.user.name.required" class="invalid-feedback">User Name is required</div>
                    <div v-if="submitted && !$v.user.name.minLength" class="invalid-feedback">Respect Points must have at least minimum {{$v.user.name.$params.minLength.min}} Value.</div>
                  </div>
              </div>
              <div class="form-group form-row">
                  <label class="col-sm-3" for="email">Email</label>
                  <div class="col-sm-9">
                    <input type="email" v-model="user.email" id="email" name="email" class="form-control" :class="{ 'is-invalid': submitted && $v.user.email.$error }" />
                    <div v-if="submitted && $v.user.email.$error" class="invalid-feedback">
                        <span v-if="!$v.user.email.required">Email is required</span>
                        <span v-if="!$v.user.email.email">Email is invalid</span>
                    </div>
                  </div>
              </div>
            </form>
          </div><!-- /col -->
        </div><!-- /row -->
      </div><!-- /container -->

      <template class="footer" #footer>
        <CButton v-on:click="onSubmit" color="primary">{{ (user.id == '') ? 'Add User' : 'Update User'  }}</CButton>    
        <CButton v-on:click="addEditModal.status = false" color="secondary">Cancel</CButton>
      </template>

    </CModal>
    <!-- End Add/Edit/Update Model -->
    
  </div>
    
</template>

<script>

const fields = [
  //{ label :'User Id', key: 'id', _style:'width:10%' },
  { label :'Username', key: 'name', },
  { label :'User Email', key: 'email', },
  { 
    key: 'show_details', 
    label: '', 
    _style: 'width:15%', 
    sorter: false, 
    filter: false
  }
]

import babelPolyfill from 'babel-polyfill'
import { RepositoryFactory } from '../../repositories/RepositoryFactory'
const UsersRepository = RepositoryFactory.get('users');

// import Vuelidate from 'vuelidate'
import { required, email, minLength, sameAs, minValue } from "vuelidate/lib/validators";
export default {
  name: 'Users',
  data () {
    return {
      items: [],
      fields,
      isLoading:false,
      noItemsView: { noResults: 'no filtering results available', noItems: 'no items available' },
      details: [],
      collapseDuration: 0,
      activePage: 1,
      deleteModal:{
        status: false,
        title: "Delete User",
        description:"Are you sure you want to delete this user ?",
        closeOnBackdrop:false,
        id:""
      },
      addEditModal:{
        status: false,
        closeOnBackdrop:false,
      },
      
      user: {
          name: "",
          email: "",
          id:""
      },
      submitted: false
    }
  },
  validations: {
    user: {
        name: {
          required,
          minLength: minLength(5)
        },
        email: { required, email }
    }
  },
  watch: {
    $route: {
      immediate: true,
      handler (route) {
        this.fetch()
        if (route.query && route.query.page) {
          this.activePage = Number(route.query.page)
        }
      }
    }
  },
  created () {
   
  },
  computed:{
  },
  methods: {

    async fetch () {
      //this.isLoading = true
      const { data } = await UsersRepository.get()
      if(data.status)
      {
        this.items = data.data;
      } else { this.items = []; }
      //this.isLoading = false
      ///this.posts = data;
    },


    toggleDetails (item) {
      this.$set(this.items[item.id], '_toggled', !item._toggled)
      this.collapseDuration = 300
      this.$nextTick(() => { this.collapseDuration = 0})
    },
    pageChange (val) {
      this.$router.push({ query: { page: val }})
    },
    
    // show delete page
    ShowDeleteModal(id) {
      this.deleteModal.id = id;
      this.deleteModal.status = true;
    },

    // delete record
    async deleteRecord() {
      const { data } = await UsersRepository.delete(this.deleteModal.id);
      if(data.status) {
        this.deleteModal.status = false;
        this.fetch();
      } else { 

      }
      this.deleteModal.status = false;
      this.deleteModal.id = "";
    },

    // show add/edit modal
    async openAddEditModal(id = '') {
      this.submitted = false;
      this.resetUserData();
      if(id != '')
      {
        this.user.id = id;
        const { data } = await UsersRepository.getUser(this.user.id);
        if(data.status) {
          this.addEditModal.status = true;
          this.user.name = data.data.name;
          this.user.email = data.data.email;
        } else { this.resetUserData(); }
      } else {
        this.addEditModal.status = true;
      }
      
    },
    
    // add update user
    async onSubmit() {
      
      this.submitted = true;
      this.$v.$touch();
      if (this.$v.$invalid) {
          return;
      }
      
      let userData = {};
      userData['email'] = this.user.email;
      userData['name'] = this.user.name;

      if(this.user.id == '') {
        const { data } = await UsersRepository.create(userData);
        if( data.status )
        {
          this.submitted = false;
          this.addEditModal.status = false;
          this.resetUserData();
          this.fetch();
        } 
      } else {
        const { data } = await UsersRepository.update(this.user.id, userData);
        if( data.status )
        {
          this.submitted = false;
          this.addEditModal.status = false;
          this.resetUserData();
          this.fetch();
        }
      }
      
    },

    // reset user data
    resetUserData() {
      this.user.name = "";
      this.user.email = "";
      this.user.id = "";
      this.addEditModal.status = false;
      this.submitted = false;
    }

  }
}
</script>