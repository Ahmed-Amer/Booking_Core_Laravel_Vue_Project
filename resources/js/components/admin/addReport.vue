<template>
    <div>
        <success v-if="reportStored">
            <i class="fa fa-thumbs-up"></i>
            <h1 class="mt-4">Success!</h1>
            <h2>Report Stored Successfully</h2>
            <p style="font-size: 0.8rem;">You will be redirected to dashboard in 4 seconds</p>
        </success>
        <div class="row" v-else>
            <h1 class="h2 mb-3" style="font-weight: 200;">Create Report</h1>

            <form @submit="formSubmit" enctype="multipart/form-data">
                <div class="col-md-8 form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" :class="[{ 'is-invalid': errorFor('title') }]" name="title"
                        v-model="report.title">
                    <span class="form-error">{{ errorFor('title') }}</span>
                </div>
                <div class="col-md-8 form-group">
                    <label for="description">Description</label>
                    <textarea name="description" cols="30" rows="10" class="form-control"
                        :class="[{ 'is-invalid': errorFor('description') }]" v-model="report.description"></textarea>
                    <span class="form-error">{{ errorFor('description') }}</span>
                </div>
                <div class="col-md-8 form-group">
                    <label for="file">File</label>
                    <input type="file" class="form-control" :class="[{ 'is-invalid': errorFor('file') }]" name="file"
                        v-on:change="onFileChange">

                    <span class="form-error">{{ errorFor('file') }}</span>
                </div>
                <div class="col-md-8 form-group">
                    <div class="d-grid gap-2">
                        <input type="submit" value="Create Report" class="btn btn-md btn-primary mt-2">
                            
                        <span v-if="loading"><i class="fas fa-spinner fa-spin"></i></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { isLoggedIn  , checkAdmin } from '../../shared/auth';

export default {
    data() {
        return {
            report: {
                title: null,
                description: null,
                file: null
            },
            errors: null,
            loading: false,
            reportStored: false,

        }
    },
    computed: {
        user_id() {
            return isLoggedIn() ? this.$store.state.user.id : null;
        },
    },
    methods: {
        onFileChange(e) {
            this.report.file = e.target.files[0];
        },
        formSubmit(e) {

            e.preventDefault();
            const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
            let data = new FormData();
            data.append('title', this.report.title);
            data.append('description', this.report.description);
            data.append('file', this.report.file);
            this.loading = true;
            this.errors = null;
            this.reportStored = false;
            axios
                .post(`/api/save-report`, data , config)
                .then(response => {
                    this.loading = false;
                    this.reportStored = true;
                    setTimeout(() => {
                        this.$router.push({ name: 'dashboard' });
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