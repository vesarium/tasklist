<template>
<div class="wrapper">
  <CCard>
    <CCardHeader>Tasks List
      <div class="card-header-actions">
        <CButton v-if="isAdmin || isMyTasksPage"  size="sm" class="pull-right" v-on:click="openAddEditModal()" color="success">Add New </CButton>
      </div>
    </CCardHeader>
      <CCardBody>
        <CDataTable
          :items="items"
          :fields="fields"
          table-filter
          :column-filter="columnFilter"
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
      
          <template #actions="{item}">

            <td class="py-2" >
              <CButton v-if="isUser && !isMyTasksPage" color="primary" square size="sm" @click="openTaskViewModal(item.id)" >
                View
              </CButton> 
              <CButton v-if="isUser && !isMyTasksPage" color="success" class="ml-2" square size="sm" @click="ShowTaskAcceptModal(item.id)" >
                Accept
              </CButton>

              <CButton
                v-if="(isUser && isMyTasksPage) || isAdmin"
                color="primary"
                square
                size="sm"
                @click="openAddEditModal(item.id)"
              >
                Edit
              </CButton> 
              <CButton
                v-if="(isUser && isMyTasksPage) || isAdmin"
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
    <CModal :title="(task.id == '') ? 'Add Task' : 'Edit Task'" :closeOnBackdrop="addEditModal.closeOnBackdrop"  :show.sync="addEditModal.status">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-12">
            <form @submit.prevent="onSubmit">
              <input type="hidden" v-model="task.id" id="id" name="id"/>
              <div class="form-group form-row">
                  <label class="col-sm-3" for="title">Task Title</label>
                  <div class="col-sm-9">
                    <input type="text" v-model="task.title" id="title" name="title" class="form-control" :class="{ 'is-invalid': submitted && $v.task.title.$error }" />
                    <div v-if="submitted && !$v.task.title.required" class="invalid-feedback">Task title is required</div>
                  </div>
              </div>
              <div class="form-group form-row">
                  <label class="col-sm-3" for="title">Task Description</label>
                  <div class="col-sm-9">
                    <textarea type="text" v-model="task.description" id="description" name="description" class="form-control" :class="{ 'is-invalid': submitted && $v.task.description.$error }" ></textarea>
                    <div v-if="submitted && !$v.task.description.required" class="invalid-feedback">Task description is required</div>
                  </div>
              </div>
              <div class="form-group form-row">
                  <label class="col-sm-3" for="duedate">Due Date</label>
                  <div class="col-sm-9">
                    <input type="text" v-model="task.duedate" id="duedate" name="duedate" class="form-control" :class="{ 'is-invalid': submitted && $v.task.duedate.$error }" />
                    <div v-if="submitted && !$v.task.duedate.required" class="invalid-feedback">Due Date is required</div>
                  </div>
              </div>
              <div class="form-group form-row">
                  <label class="col-sm-3" for="respectpoints">Respect Points</label>
                  <div class="col-sm-9">
                    <input type="number" v-model="task.respectpoints" id="respectpoints" name="respectpoints" class="form-control" :class="{ 'is-invalid': submitted && $v.task.respectpoints.$error }" />
                    <div v-if="submitted && !$v.task.respectpoints.required" class="invalid-feedback">Respect Points is required</div>
                    <div v-if="submitted && !$v.task.respectpoints.minValue" class="invalid-feedback">Respect Points must have at least minimum {{$v.task.respectpoints.$params.minValue.min}} Value.</div>
                  </div>
              </div>
            </form>
          </div><!-- /col -->
        </div><!-- /row -->
      </div><!-- /container -->

      <template class="footer" #footer>
        <CButton v-on:click="onSubmit" color="primary">{{ (task.id == '') ? 'Add Task' : 'Update Task'  }}</CButton>    
        <CButton v-on:click="addEditModal.status = false" color="secondary">Cancel</CButton>
      </template>

    </CModal>
    <!-- End Add/Edit/Update Model -->


    <!-- View Task Model -->
    <CModal :title="ViewTaskModal.title" :closeOnBackdrop="ViewTaskModal.closeOnBackdrop"  :show.sync="ViewTaskModal.status">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-12">
            <form @submit.prevent="onSubmit">
              <input type="hidden" v-model="task.id" id="id" name="id"/>
              <div class="form-group form-row mb-0">
                  <label class="col-sm-12" for="title">Task Title : {{ ViewTaskModal.task.title }}</label>
                  <label class="col-sm-12" for="title">Task Description : {{ ViewTaskModal.task.description }}</label>
                  <label class="col-sm-12" for="title">Due Date : {{ ViewTaskModal.task.duedate }}</label>
                  <label class="col-sm-12" for="title">Respect Points : {{ ViewTaskModal.task.respectpoints }}</label>
                  <label class="col-sm-12" for="title">Status : {{ ViewTaskModal.task.status }}</label>
                  <label class="col-sm-12" for="title">Accepted By : {{ ViewTaskModal.task.acceptedby }}</label>
              </div>
            </form>
          </div><!-- /col -->
        </div><!-- /row -->
      </div><!-- /container -->
      <template #footer>
        <CButton v-on:click="ViewTaskModal.status = false" color="secondary">Cancel</CButton>
      </template>
    </CModal>
    <!-- End View Task Model -->


    <!-- Delete Model -->
    <CModal :title="taskAcceptModal.title" :closeOnBackdrop="taskAcceptModal.closeOnBackdrop"  :show.sync="taskAcceptModal.status">
      {{ taskAcceptModal.description }}
      <template #footer>
        <CButton v-on:click="taskAcceptRecord(true)" color="success"> Accept</CButton>
        <CButton v-on:click="taskAcceptModal.status = false" color="secondary">Cancel</CButton>
      </template>
    </CModal>
    <!-- End delete Model -->
    
  </div>
    
</template>

<script>
const items = [
  { id:1, title: 'Samppa Nori', email: 'raviraj@gmail.com', role: 'Member', status: 'Active'},
  { id:2, title: 'Estavan Lykos', email: 'raviraj@gmail.com', role: 'Staff', status: 'Banned'},
  { id:3, title: 'Chetan Mohamed', email: 'raviraj@gmail.com', role: 'Admin', status: 'Inactive'},
  { id:4, title: 'Derick Maximinus', email: 'raviraj@gmail.com', role: 'Member', status: 'Pending'},
  { id:5, title: 'Friderik Dávid', email: 'raviraj@gmail.com', role: 'Staff', status: 'Active'},
  { id:6, title: 'Yiorgos Avraamu', email: 'raviraj@gmail.com', role: 'Member', status: 'Active'},
  { id:7, title: 'Avram Tarasios', email: 'raviraj@gmail.com', role: 'Staff', status: 'Banned', _classes: 'table-success'},
  { id:8, title: 'Quintin Ed', email: 'raviraj@gmail.com', role: 'Admin', status: 'Inactive'},
  { id:9, title: 'Enéas Kwadwo', email: 'raviraj@gmail.com', role: 'Member', status: 'Pending'},
  { id:10, title: 'Agapetus Tadeáš', email: 'raviraj@gmail.com', role: 'Staff', status: 'Active'},
  { id:1, title: 'Carwyn Fachtna', email: 'raviraj@gmail.com', role: 'Member', status: 'Active', _classes: 'table-info'},
  { id:1, title: 'Nehemiah Tatius', email: 'raviraj@gmail.com', role: 'Staff', status: 'Banned'},
  { id:1, title: 'Ebbe Gemariah', email: 'raviraj@gmail.com', role: 'Admin', status: 'Inactive'},
  { id:1, title: 'Eustorgios Amulius', email: 'raviraj@gmail.com', role: 'Member', status: 'Pending'},
  { id:1, title: 'Leopold Gáspár', email: 'raviraj@gmail.com', role: 'Staff', status: 'Active'},
  { id:1, title: 'Pompeius René', email: 'raviraj@gmail.com', role: 'Member', status: 'Active'},
  { id:1, title: 'Paĉjo Jadon', email: 'raviraj@gmail.com', role: 'Staff', status: 'Banned'},
  { id:1, title: 'Micheal Mercurius', email: 'raviraj@gmail.com', role: 'Admin', status: 'Inactive'},
  { id:1, title: 'Ganesha Dubhghall', email: 'raviraj@gmail.com', role: 'Member', status: 'Pending'},
  { id:1, title: 'Hiroto Šimun', email: 'raviraj@gmail.com', role: 'Staff', status: 'Active'},
  { id:1, title: 'Vishnu Serghei', email: 'raviraj@gmail.com', role: 'Member', status: 'Active'},
  { id:1, title: 'Zbyněk Phoibos', email: 'raviraj@gmail.com', role: 'Staff', status: 'Banned'},
  { id:1, title: 'Einar Randall', email: 'raviraj@gmail.com', role: 'Admin', status: 'Inactive', _classes: 'table-danger'},
  { id:1, title: 'Félix Troels', email: 'raviraj@gmail.com', role: 'Staff', status: 'Active'},
  { id:1, title: 'Aulus Agmundr', email: 'raviraj@gmail.com', role: 'Member', status: 'Pending'}
]
// _classes:'d-none'
const fields = [
  //{ label :'User Id', key: 'id', _style:'width:10%' },
  { label :'Title', key: 'title', },
  { label :'Description', key: 'title', },
  { label :'Due Date', key: 'title', },
  { label :'RP', key: 'title', },
  { label :'Accepted By', key: 'title', sorter: false },
  { label :'Status', key: 'title', sorter: false },
  { 
    key: 'actions', 
    label: '', 
    _style: 'width:15%', 
    sorter: false, 
    filter: false
  }
]

const fieldsTasks = [
  //{ label :'User Id', key: 'id', _style:'width:10%' },
  { label :'Title', key: 'title', },
  { label :'Description', key: 'title', },
  { label :'Posted By', key: 'title', },
  { label :'Due Date', key: 'title', },
  { label :'RP', key: 'title', },
  { label :'Accepted By', key: 'title', sorter: false },
  { label :'Status', key: 'title', sorter: false },
  { 
    key: 'actions', 
    label: '', 
    _style: 'width:15%', 
    sorter: false, 
    filter: false
  }
]

import store from "../../store";
import { Role } from '../../_helpers/role';
// import Vuelidate from 'vuelidate'
import { required, minValue, email, midLength, sameAs } from "vuelidate/lib/validators";
import babelPolyfill from 'babel-polyfill'
import { RepositoryFactory } from '../../repositories/RepositoryFactory'
const TasksRepository = RepositoryFactory.get('tasks');

export default {
  name: 'Tasks',
  data () {
    return {
      items: items.map((item,key) => { return {...item,key}}),
      fields,
      isLoading:false,
      columnFilter:false,
      noItemsView: { noResults: 'no filtering results available', noItems: 'no items available' },
      details: [],
      collapseDuration: 0,
      activePage: 1,
      deleteModal:{
        status: false,
        title: "Delete User",
        description:"Are you sure you want to delete this user ?",
        closeOnBackdrop:true,
        id:0
      },
      taskAcceptModal:{
        status: false,
        title: "Confirm",
        description:"Are you sure you want to accept this task ?",
        closeOnBackdrop:true,
        id:0
      },
      addEditModal:{
        status: false,
        closeOnBackdrop:true,
      },
      ViewTaskModal:{
        status: false,
        title: "Task Details",
        closeOnBackdrop:true,
        task:{
          title: "sdfdsf",
          description: "sdfdf",
          duedate: "2020-04-29",
          respectpoints: "33",
          status:'Added',
          acceptedby:'raviraj',
        }
      },
      
      task: {
          title: "",
          description: "",
          duedate: "",
          respectpoints: "",
          id:""
      },
      submitted: false,

      pageName : ""
    }
  },
  validations: {
    task: {
        title: { required },
        description: { required },
        duedate: { required },
        respectpoints: {
          required,
          minValue: minValue(1)
        },
    }
  },
  watch: {
    $route: {
      immediate: true,
      handler (route) {
        this.pageName = route.path;
        if(route.path == '/tasks')
        { 
          //this.fields.splice(2,0);
          this.fields = fieldsTasks;
        } else { this.fields = fields; }
        this.fetch();
        if (route.query && route.query.page) {
          this.activePage = Number(route.query.page)
        }
      }
    }
  },
  computed: {
    isAdmin () {
      return store.getters.isAuthenticated && store.getters.role === Role.ROLE_ADMIN;
    },
    isUser () {
      return store.getters.isAuthenticated && store.getters.role === Role.ROLE_USER;
    },
    isMyTasksPage () {
      return this.pageName == "/my-tasks";
    }
  },
  methods: {
    toggleDetails (item) {
      this.$set(this.items[item.id], '_toggled', !item._toggled)
      this.collapseDuration = 300
      this.$nextTick(() => { this.collapseDuration = 0})
    },
    pageChange (val) {
      this.$router.push({ query: { page: val }})
    },
    
    async fetch () {
      //this.isLoading = true
      if(this.isMyTasksPage) {
        //const { data } = await TasksRepository.getMyTasks()
        const { data } = await TasksRepository.get()
        if(data.status)
        {
          this.items = data.data;
        } else { this.items = []; }
      } else {
        const { data } = await TasksRepository.get()
        if(data.status)
        {
          this.items = data.data;
        } else { this.items = []; }
      }

      //this.isLoading = false
      ///this.posts = data;
    },

     // show delete page
    ShowTaskAcceptModal(id) {
      this.taskAcceptModal.id = id;
      this.taskAcceptModal.status = true;
    },

    // accept task
    async taskAcceptRecord(status) {
        const { data } = await TasksRepository.approveTask(this.taskAcceptModal.id)
        if(data.status) {
          this.taskAcceptModal.status = false;
          this.taskAcceptModal.id = 0;
          this.fetch();
        } else { 

        }        
    },


    // task detail
    openTaskViewModal(id) {
      this.ViewTaskModal.id = id;
      this.ViewTaskModal.status = true;
    },

    // show delete page
    ShowDeleteModal(id) {
      this.deleteModal.id = id;
      this.deleteModal.status = true;
    },

    // delete record
    async deleteRecord() {
      const { data } = await TasksRepository.delete(this.deleteModal.id)
      if(data.status) {
        this.deleteModal.status = false;
        this.fetch();
      } else { 

      }
      this.deleteModal.status = false;
      this.deleteModal.id = 0;
    },

    // show add/edit modal
    async openAddEditModal(id = '') {
      this.resetTaskData();
      if(id != '')
      {
        this.task.id = id;
        const { data } = await TasksRepository.getTask(this.task.id);
        if(data.status) {
          this.addEditModal.status = true;
          this.task.title = data.data.title;
          this.task.description = data.data.description;
          this.task.duedate = data.data.duedate;
          this.task.respectpoints = data.data.respectpoints;
        } else { this.resetTaskData(); }
      }
      this.addEditModal.status = true;
    },
    
    async onSubmit() {
        this.submitted = true;
        this.$v.$touch();
        if (this.$v.$invalid) {
            return;
        }

        let taskData = {};
        taskData['title'] = this.task.title;
        taskData['description'] = this.task.description;
        taskData['duedate'] = this.task.duedate;
        taskData['respectpoints'] = this.task.respectpoints;

        if(this.task.id == '') {
          const { data } = await TasksRepository.create(taskData);
          if( data.status )
          {
            this.submitted = false;
            this.addEditModal.status = false;
            this.resetTaskData();
            this.fetch();
          } 
        } else {
          const { data } = await TasksRepository.update(this.task.id, taskData);
          if( data.status )
          {
            this.submitted = false;
            this.addEditModal.status = false;
            this.resetTaskData();
            this.fetch();
          }
        }
    },
    
    // reset user data
    resetTaskData() {
      this.submitted = false;
      this.task.title = "";
      this.task.description = "";
      this.task.duedate = "";
      this.task.respectpoints = "";
      this.task.id = "";
    }

  }
}
</script>