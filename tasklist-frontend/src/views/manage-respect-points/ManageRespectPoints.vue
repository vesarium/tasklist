<template>
<div class="wrapper">
  <CCard class="col-md-6 px-0">
    <CCardHeader> Manage Respect Points </CCardHeader>
        <CCardBody>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <form @submit.prevent="onSubmit">
                            <input type="hidden" v-model="user.id" id="id" name="id"/>
                            <div class="form-group form-row mb-0">
                                <label class="col-sm-4" for="points">Your Respect Points</label>
                                <div class="col-sm-8">
                                    <input type="number" v-model="user.points" id="points" name="points" class="form-control" :class="{ 'is-invalid': submitted && $v.user.points.$error }" />
                                    <div v-if="submitted && !$v.user.points.required" class="invalid-feedback">Respect Points is required</div>
                                    <div v-if="submitted && !$v.user.points.minValue" class="invalid-feedback">Respect Points must have at least minimum {{$v.user.points.$params.minValue.min}} Value.</div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </CCardBody>
        <CCardFooter>
          <div class="card-header-actions">
              <CButton v-on:click="onSubmit" color="success">Update</CButton>    
          </div>
        </CCardFooter>
    </CCard>
  </div>
    
</template>

<script>

// import Vuelidate from 'vuelidate'
import { required, minValue , email, minLength, sameAs } from "vuelidate/lib/validators";
export default {
  name: 'ManageRespectPoints',
  data () {
    return {
      user: {
          points: "",
          id:""
      },
      submitted: false
    }
  },
  validations: {
    user: {
        points: { 
            required ,
            'minValue':minValue(0)
        },
    }
  },
  watch: {

  },
  computed:{
      
  },
  methods: {
    // show add/edit modal
    openAddEditModal(id = '') {
      this.submitted = false;
      this.user.points = "";
      this.user.id = "";
      if(id != '')
      {
        this.user.id = id;
      }
    },
    
    onSubmit() {
        this.submitted = true;
        this.$v.$touch();
        if (this.$v.$invalid) {
            return;
        }alert('sfd');
    }

  }
}
</script>