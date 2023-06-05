<template>
    <div>
        <success v-if="bookableStored">
            <i class="fa fa-thumbs-up"></i>
            <h1 class="mt-4">Success!</h1>
            <h2>Bookable Stored Successfully</h2>
            <p style="font-size: 0.8rem;">You will be redirected to dashboard in 4 seconds</p>
        </success>
        <div class="row" v-else>
            <h1 class="h2 mb-3" style="font-weight: 200;">Create Bookable</h1>
            <div class="col-md-8 form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" :class="[{ 'is-invalid': errorFor('title') }]" name="title"
                    v-model="bookable.title">
                <span class="form-error">{{ errorFor('title') }}</span>
            </div>
            <div class="col-md-8 form-group">
                <label for="description">Description</label>
                <textarea name="description" cols="30" rows="10" class="form-control"
                    :class="[{ 'is-invalid': errorFor('description') }]" v-model="bookable.description"></textarea>
                <span class="form-error">{{ errorFor('description') }}</span>
            </div>
            <div class="col-md-8 form-group">
                <label for="price">Price</label>
                <input type="text" class="form-control" :class="[{ 'is-invalid': errorFor('price') }]" name="price"
                    v-model="bookable.price">
                <span class="form-error">{{ errorFor('price') }}</span>
            </div>

            <div class="col-md-8 form-group">
                <div class="d-grid gap-2">
                    <buttton class="btn btn-md btn-primary mt-2" @click="saveBookable">
                        Create Bookable
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
            bookable: {
                title: null,
                description: null,
                price: null,
            },
            errors: null,
            loading: false,
            bookableStored: false,

        }
    },
    computed: {
        user_id() {
            return isLoggedIn() ? this.$store.state.user.id : null;
        },
        // bookableStored() {
        //     return !this.loading && this.itemsLength == 0 && this.bookingAttempted;
        // }
    },
    methods: {
        saveBookable() {
            this.loading = true;
            this.errors = null;
            this.bookableStored = false;
            axios
                .post(`/api/save-bookable`, this.bookable)
                .then(response => {
                    this.loading = false;
                    this.bookableStored = true;
                    setTimeout(()=>{
                        this.$router.push({name: 'dashboard'});
                    }, 3000);
                    
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