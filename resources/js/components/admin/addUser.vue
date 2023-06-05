<template>
    <div>
        <success v-if="userStored">
            <i class="fa fa-thumbs-up"></i>
            <h1 class="mt-4">Success!</h1>
            <h2>User Stored Successfully</h2>
            <p style="font-size: 0.8rem;">You will be redirected to dashboard in 4 seconds</p>
        </success>
        <div class="row" v-else>
            <h1 class="h2 mb-3" style="font-weight: 200;">Create User</h1>
            <div class="col-md-8 form-group">
                <label for="name">Username</label>
                <input type="text" class="form-control" :class="[{ 'is-invalid': errorFor('name') }]" name="name"
                    v-model="user.name">
                <span class="form-error">{{ errorFor('name') }}</span>
            </div>
            <div class="col-md-8 form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" :class="[{ 'is-invalid': errorFor('email') }]" name="email"
                    v-model="user.email">
                <span class="form-error">{{ errorFor('email') }}</span>
            </div>
            <div class="col-md-8 form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" :class="[{ 'is-invalid': errorFor('password') }]" name="password"
                    v-model="user.password">
                <span class="form-error">{{ errorFor('password') }}</span>
            </div>
            <div class="col-md-8 form-group">
                <label for="password">Confirm Password</label>
                <input type="password" class="form-control" :class="[{ 'is-invalid': errorFor('password_confirmation') }]" name="password_confirmation"
                    v-model="user.password_confirmation">
                <span class="form-error">{{ errorFor('password_confirmation') }}</span>
            </div>

            <div class="col-md-8 form-group">
                <div class="d-grid gap-2">
                    <buttton class="btn btn-md btn-primary mt-2" @click="saveUser">
                        Create User
                        <span v-if="loading"><i class="fas fa-spinner fa-spin"></i></span>
                    </buttton>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { isLoggedIn  , checkAdmin } from '../../shared/auth';

export default {
    data() {
        return {
            user: {
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
            },
            errors: null,
            loading: false,
            userStored: false,

        }
    },
    methods: {
        saveUser() {
            this.loading = true;
            this.errors = null;
            this.userStored = false;
            axios
                .post(`/api/save-user`, this.user)
                .then(response => {
                    this.loading = false;
                    this.userStored = true;
                    setTimeout(()=>{
                        this.$router.push({name: 'dashboard'});
                    }, 4000);
                    
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                })

        },
        errorFor(field) {
            return this.errors != null && this.errors[field] ? this.errors[field][0] : null;
        }
    },
    beforeRouteEnter(to, from, next) {
        if (isLoggedIn() && checkAdmin()) {
                next();
            } else {
                isLoggedIn() ? next({ name: 'home' }) : next({ name: 'login' });

            }

    }

}
</script>
<style scoped>
.warning {
    font-size: 1rem;
}

.form-error {
    font-size: 0.6rem;
    color: red;
}

</style>