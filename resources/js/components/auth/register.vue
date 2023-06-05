<template>
    <div class="row justify-content-center">
       <div class="col-md-6">
          <div class="card card-body">
             <form method="POST" action="#">
 
                <div class="form-group mb-3">
                   <label for="name">Name</label>
                   <input id="name" type="text" class="form-control" :class="[{ 'is-invalid': errorFor('name') }]"
                      name="name" v-model="user.name" required autofocus>
                   <span class="form-error">{{ errorFor('name') }}</span>
                </div>

                <div class="form-group mb-3">
                   <label for="email">Email</label>
                   <input id="email" type="email" class="form-control" :class="[{ 'is-invalid': errorFor('email') }]"
                      name="email" v-model="user.email" required autofocus>
                   <span class="form-error">{{ errorFor('email') }}</span>
                </div>

                <div class="form-group mb-3">
                   <label for="password">Password</label>
                   <input id="password" type="password" class="form-control"
                      :class="[{ 'is-invalid': errorFor('password') }]" name="password" v-model="user.password" required>
                   <span class="form-error">{{ errorFor('password') }}</span>
                </div>

                <div class="form-group mb-3">
                   <label for="password_confirmation">Confirm Password</label>
                   <input id="password_confirmation" type="password" class="form-control"
                      :class="[{ 'is-invalid': errorFor('password_confirmation') }]" name="password_confirmation" v-model="user.password_confirmation" required>
                   <span class="form-error">{{ errorFor('password_confirmation') }}</span>
                </div>
 
                <div class="d-grid gap-2">
                   <button type="submit" class="btn btn-md btn-primary" :disabled="loading" @click.prevent="register">
                      Sign-in
                   </button>
                </div>
                <hr>
 
                <div class="mb-0" style="font-size: small;">
 
                   <div>
                      Already have an account?
                      <router-link :to="{ name: 'login' }"
                         style="font-weight: 600; color: #0d6efd;"><a>Login</a></router-link>
                   </div>
                
                </div>
             </form>
          </div>
       </div>
    </div>
 </template>
 

 <script>
 import { logIn } from '../../shared/auth';
 import { mapState } from 'vuex'
 
 export default {
    data() {
       return {
        user: {
            name : null,
            email: null,
            password: null,
            password_confirmation: null
        },
          errors: null,
          loading: false
       }
    },
    computed : {
      ...mapState({
        isAdmin: 'isAdmin'
      }),
    },
    methods: {
       async register() {
          this.loading = true;
          this.errors = null;
 
          try {
             const response = await axios.post('/register' , this.user);
            if(201 == response.status){
                logIn();
                await this.$store.dispatch("loadUser");
                this.isAdmin ? this.$router.push({name : "dashboard"}) : this.$router.push({name : "home"});
            }
          } catch (error) {
             this.errors = error.response && error.response.data.errors;
          }
 
          this.loading = false;
       },
       errorFor(field) {
          return this.errors != null && this.errors[field] ? this.errors[field][0] : null;
       }
    }
 }
 </script>
 <style scoped>
 .form-error {
    font-size: 0.6rem;
    color: red;
 }</style>
 