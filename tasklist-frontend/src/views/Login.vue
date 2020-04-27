<template>
  <div class="d-flex align-items-center min-vh-100">
    <CContainer fluid>
      <CRow class="justify-content-center">
        <CCol md="4">
          <CCard class="">
            <CCardBody class="p-4">
              <form @submit.prevent="checkLogin" validate>
                <CRow class="justify-content-center p-4">
                  <h1 class="text-center">Login</h1>
                </CRow>
                <CAlert
                  color="danger"
                  closeButton
                  :show.sync="error"
                >
                  Invalid Credentials
                </CAlert>
                <CInput
                  required
                  valid-feedback="Thank you :)"
                  invalid-feedback="Please provide at least 4 characters."
                  placeholder="Email"
                  type="email"
                  autocomplete="email"
                  prepend="@"
                  v-model="email">
                </CInput>
                <CInput
                  valid-feedback="Thank you :)"
                  invalid-feedback="Please provide at least 4 characters."
                  placeholder="Password"
                  type="password"
                  autocomplete="new-password"
                  required
                  v-model="password"
                >
                  <template #prepend-content>
                    <CIcon name="cil-lock-locked"/>
                  </template>
                </CInput>
                <CRow class="justify-content-center">
                  <div class="form-group form-actions">
                    <CButton type="submit" color="success">Login</CButton>
                  </div>
                </CRow>
              </form>
            </CCardBody>
          </CCard>
        </CCol>
      </CRow>
    </CContainer>
  </div>
</template>

<script>
import { AUTH_REQUEST } from "../store/actions/auth";
import axios from 'axios'

export default {
  name: "Login",
  data() {
    return {
      error: false,
      email: "",
      password: ""
    };
  },
  methods: {
    checkLogin: function() {
      let self = this;
      const { email, password } = this;
      this.$store.dispatch(AUTH_REQUEST, { email, password }).then((result) => {
        if(result.status) { self.$router.push('/dashboard'); } {
          this.error = true;
          this.email = result.message;
        }
      });
    }
  }
};
</script>
