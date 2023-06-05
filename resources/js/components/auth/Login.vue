<template>
   <div class="row justify-content-center">
      <div class="col-md-6">
         <div class="card card-body">
            <form method="POST" action="#">


               <div class="form-group mb-3">
                  <label for="email">Email</label>
                  <input id="email" type="email" class="form-control" :class="[{ 'is-invalid': errorFor('email') }]"
                     name="email" v-model="email" required autofocus>
                  <span class="form-error">{{ errorFor('email') }}</span>
               </div>
               <div class="form-group mb-3">
                  <label for="password">Password</label>
                  <input id="password" type="password" class="form-control"
                     :class="[{ 'is-invalid': errorFor('password') }]" name="password" v-model="password" required>
                  <span class="form-error">{{ errorFor('password') }}</span>
               </div>

               <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-md btn-primary" :disabled="loading" @click.prevent="login">
                     Login
                  </button>
               </div>
               <hr>

               <div class="mb-0" style="font-size: small;">

                  <div>
                     No account yet?
                     <router-link :to="{ name: 'register' }"
                        style="font-weight: 600; color: #0d6efd;"><a>Register</a></router-link>
                  </div>
                  <div>
                     Forgot Your Password?
                     <router-link :to="{ name: 'home' }" style="font-weight: 600; color: #0d6efd;"><a>Rest
                           password</a></router-link>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</template>

<script>
import { logIn } from '../../shared/auth';
import { mapState } from 'vuex';
export default {
   data() {
      return {
         email: "",
         password: "",
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
      async login() {
         this.loading = true;
         this.errors = null;

         try {
            await axios.get('/sanctum/csrf-cookie');

            await axios.post('/login', {
               email: this.email,
               password: this.password
            });

            logIn();
            await this.$store.dispatch("loadUser");
            this.isAdmin ? this.$router.push({name : "dashboard"}) : this.$router.push({name : "home"});

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
