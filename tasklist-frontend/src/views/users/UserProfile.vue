<template>
  <div class="wrapper">
    <CCard>
      <CCardHeader>Needs
        <div class="card-header-actions">
          <CButton size="sm" class="pull-right" v-on:click="openAddEditNeedsModal()" color="success">Add Needs</CButton>
        </div>
      </CCardHeader>
      <CCardBody>
        <CDataTable
          :items="list.needs.items"
          :fields="list.needs.fields"
          table-filter
          :loading = "needs.isLoading"
          items-per-page-select
          :items-per-page="5"
          :active-page="list.needs.activePage"
          hover
          sorter
          pagination
          :noItemsView="list.needs.noItemsView"
        >
        </CDataTable>
      </CCardBody>
    </CCard>

        <!-- Add/Edit/Update Model -->
    <CModal :title="(needs.id == '') ? 'Add Needs' : 'Edit Needs'" :closeOnBackdrop="addEditNeedsModal.closeOnBackdrop"  :show.sync="addEditNeedsModal.status">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-12">
            <form @submit.prevent="onSubmitNeeds">
              <input type="hidden" v-model="needs.id" id="id" name="id"/>
              <div class="form-group form-row">
                  <label class="col-sm-3" for="name">Need</label>
                  <div class="col-sm-9">
                    <input type="text" v-model="needs.name" id="name" name="name" class="form-control" :class="{ 'is-invalid': submittedNeeds && $v.needs.name.$error }" />
                    <div v-if="submittedNeeds && !$v.needs.name.required" class="invalid-feedback">Need is required</div>
                    <div v-if="submittedNeeds && !$v.needs.name.minLength" class="invalid-feedback">Capabilities must have at least minimum {{$v.needs.name.$params.minLength.min}} Value.</div>
                  </div>
              </div>
            </form>
          </div><!-- /col -->
        </div><!-- /row -->
      </div><!-- /container -->

      <template class="footer" #footer>
        <CButton v-on:click="onSubmitNeeds" color="primary">{{ (needs.id == '') ? 'Add Needs' : 'Update User'  }}</CButton>    
        <CButton v-on:click="resetNeedsData" color="secondary">Cancel</CButton>
      </template>

    </CModal>
    <!-- End Add/Edit/Update Model -->
    

    <CCard>
      <CCardHeader>Capabilities
        <div class="card-header-actions">
          <CButton size="sm" class="pull-right" v-on:click="openAddEditCapabilitiesModal()" color="success">Add Capabilities</CButton>
        </div>
      </CCardHeader>
      <CCardBody>
        <CDataTable
          :items="list.capabilities.items"
          :fields="list.capabilities.fields"
          table-filter
          :loading = "capabilities.isLoading"
          items-per-page-select
          :items-per-page="5"
          :active-page="list.capabilities.activePage"
          hover
          sorter
          pagination
          :noItemsView="list.capabilities.noItemsView"
        >
        </CDataTable>
      </CCardBody>
    </CCard>


        <!-- Add/Edit/Update Model -->
    <CModal :title="(capabilities.id == '') ? 'Add Capabilities' : 'Edit Capabilities'" :closeOnBackdrop="addEditCapabilitiesModal.closeOnBackdrop"  :show.sync="addEditCapabilitiesModal.status">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-12">
            <form @submit.prevent="onSubmitCapabilities">
              <input type="hidden" v-model="capabilities.id" id="id" name="id"/>
              <div class="form-group form-row">
                  <label class="col-sm-3" for="name">Capabilities</label>
                  <div class="col-sm-9">
                    <input type="text" v-model="capabilities.name" id="name" name="name" class="form-control" :class="{ 'is-invalid': submittedCapabilities && $v.capabilities.name.$error }" />
                    <div v-if="submittedCapabilities && !$v.capabilities.name.required" class="invalid-feedback">Capabilities is required</div>
                    <div v-if="submittedCapabilities && !$v.capabilities.name.minLength" class="invalid-feedback">Capabilities must have at least minimum {{$v.capabilities.name.$params.minLength.min}} Value.</div>
                  </div>
              </div>
            </form>
          </div><!-- /col -->
        </div><!-- /row -->
      </div><!-- /container -->

      <template class="footer" #footer>
        <CButton v-on:click="onSubmitCapabilities" color="primary">{{ (capabilities.id == '') ? 'Add Capabilities' : 'Update Capabilities'  }}</CButton>    
        <CButton v-on:click="resetCapabilitiesData" color="secondary">Cancel</CButton>
      </template>

    </CModal>
    <!-- End Add/Edit/Update Model -->







  </div>
    
</template>

<script>

const fields = [
  { label :'Username', key: 'name' }
]

import babelPolyfill from 'babel-polyfill'
import { RepositoryFactory } from '../../repositories/RepositoryFactory'
const NeedsRepository = RepositoryFactory.get('needs');
const CapabilitiesRepository = RepositoryFactory.get('capabilities');

// import Vuelidate from 'vuelidate'
import { required, email, minLength, sameAs, minValue } from "vuelidate/lib/validators";
export default {
  name: 'UsersProfile',
  data () {
    return {
      list:{
        needs : {
          items: [],
          fields: [{ label :'Needs', key: 'name' }],
          isLoading:false,
          noItemsView: { noResults: 'no filtering results available', noItems: 'no items available' },
          details: [],
          collapseDuration: 0,
          activePage: 1,
        },
        capabilities : {
          items: [],
          fields: [{ label :'Capabilities', key: 'name' }],
          isLoading:false,
          noItemsView: { noResults: 'no filtering results available', noItems: 'no items available' },
          details: [],
          collapseDuration: 0,
          activePage: 1,
        },
      },
      addEditNeedsModal:{
        status: false,
        closeOnBackdrop:false,
      },
      addEditCapabilitiesModal:{
        status: false,
        closeOnBackdrop:false,
      },
      needs: {
          name: "",
          id:""
      },
      capabilities: {
          name: "",
          id:""
      },
      submittedNeeds: false,
      submittedCapabilities: false
    }
  },
  validations: {
    needs: {
        name: {
          required,
          minLength: minLength(5)
        },
    },
    capabilities: {
        name: {
          required,
          minLength: minLength(5)
        },
    }
  },
  watch: {
    $route: {
      immediate: true,
      handler (route) {
        this.fetchNeeds()
        this.fetchCapabilities()
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

    async fetchNeeds () {
      //this.isLoading = true
      const { data } = await NeedsRepository.get()
      if(data.status)
      {
        this.list.needs.items = data.data;
      } else { this.list.needs.items = []; }
      //this.isLoading = false
    },

    // show add/edit modal
    async openAddEditNeedsModal(id = '') {
      this.submittedNeeds = false;
      if(id != '')
      {
        this.needs.id = id;
        const { data } = await NeedsRepository.getUser(this.needs.id);
        if(data.status) {
          this.addEditNeedsModal.status = true;
          this.needs.name = data.data.name;
        } else { this.resetNeedsData(); }
      } else {
        this.addEditNeedsModal.status = true;
      }
    },
    
    // add update user
    async onSubmitNeeds() {
      
      this.submittedNeeds = true;
      this.$v.needs.$touch();
      if (this.$v.needs.$invalid) {
          return;
      }
      
      let userData = {};
      userData['name'] = this.needs.name;

      if(this.needs.id == '') {
        const { data } = await NeedsRepository.create(userData);
        if( data.status )
        {
          this.submittedNeeds = false;
          this.addEditNeedsModal.status = false;
          this.resetNeedsData();
          this.fetchNeeds();
        } 
      } else {
        const { data } = await NeedsRepository.update(this.needs.id, userData);
        if( data.status )
        {
          this.submittedNeeds = false;
          this.addEditNeedsModal.status = false;
          this.resetNeedsData();
          this.fetchNeeds();
        }
      }
    },

    // reset user data
    resetNeedsData() {
      this.needs.name = "";
      this.needs.id = "";
      this.addEditNeedsModal.status = false;
      this.submittedNeeds = false;
    },


    async fetchCapabilities () {
      //this.isLoading = true
      const { data } = await CapabilitiesRepository.get()
      if(data.status)
      {
        this.list.capabilities.items = data.data;
      } else { this.list.capabilities.items = []; }
      //this.isLoading = false
    },

    // show add/edit modal
    async openAddEditCapabilitiesModal(id = '') {
      
      this.submittedCapabilities = false;
      if(id != '')
      {
        this.capabilities.id = id;
        const { data } = await CapabilitiesRepository.getUser(this.capabilities.id);
        
        if(data.status) {
          this.addEditCapabilitiesModal.status = true;
          this.capabilities.name = data.data.name;
        } else { this.resetCapabilitiesData(); }
      } else {
        this.addEditCapabilitiesModal.status = true;
      }
    },
    
    // add update user
    async onSubmitCapabilities() {
      
      this.submittedCapabilities = true;
      this.$v.capabilities.$touch();
      if (this.$v.capabilities.$invalid) {
          return;
      }
      
      let userData = {};
      userData['name'] = this.capabilities.name;

      if(this.capabilities.id == '') {
        const { data } = await CapabilitiesRepository.create(userData);
        if( data.status )
        {
          this.submittedCapabilities = false;
          this.addEditCapabilitiesModal.status = false;
          this.resetCapabilitiesData();
          this.fetchNeeds();
        } 
      } else {
        const { data } = await CapabilitiesRepository.update(this.capabilities.id, userData);
        if( data.status )
        {
          this.submittedCapabilities = false;
          this.addEditCapabilitiesModal.status = false;
          this.resetCapabilitiesData();
          this.fetchCapabilities();
        }
      }
    },

    // reset user data
    resetCapabilitiesData() {
      this.capabilities.name = "";
      this.capabilities.id = "";
      this.addEditCapabilitiesModal.status = false;
      this.submittedCapabilities = false;
    }

  }
}
</script>