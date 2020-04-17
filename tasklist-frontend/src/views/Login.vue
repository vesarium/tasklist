<template>
  <div class="d-flex align-items-center min-vh-100">
    <CContainer fluid>
      <CRow class="justify-content-center">
        <CCol md="4">
          <CCard class="">
            <CCardBody class="p-4">
              <form @submit.prevent="checkLogin" validate>
                <CAlert
                  color="danger"
                  closeButton
                  :show.sync="error"
                >
                  Invalid Credentials
                </CAlert>
                <CRow class="justify-content-center p-4">
                  <h1 class="text-center">Login</h1>
                </CRow>
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
    checkLogin() {
      let self = this;
      axios.post("/login_check", {
          email: this.email,
          password: this.password
        })
        .then(function(response) {
          localStorage.token = response.data.token;
          self.$router.push('/dashboard');
        })
        .catch(function(error) {
          self.error = true;
          delete localStorage.token;
        });
    }
  }
};
</script>
