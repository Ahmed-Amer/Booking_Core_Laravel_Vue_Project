<template>
    <div>
        <success v-if="reviewStored">
            <i class="fa fa-thumbs-up"></i>
            <h1 class="mt-4">Success!</h1>
            <h2>Your review has been added!</h2>
            <p style="font-size: 0.8rem;">You will be redirected to profile page in 4 seconds</p>
        </success>
        <div class="row" v-else>
            <div class="col-md-12" v-if="loading">Loading....</div>
            <div class="col-md-12" v-else>
                <!-- <div class="error-style" v-if="hasReview">
                    <h2>You already left a review for this booking</h2>
                </div> -->
                <error v-if="hasReview">
                    <i class="fa fa-bomb"></i>
                    <h1 class="mt-4">Blocked!</h1>
                    <h2>You already left a review for this booking</h2>
                </error>

                <div v-else>
                    <div v-if="hasBooking">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <p>
                                            Stayed at
                                            <router-link style="color:#0d6efd;"
                                                :to="{ name: 'showbookable', params: { id: booking.bookable.id } }">
                                                {{ booking.bookable.title }}
                                            </router-link>
                                        </p>
                                        <p>From {{ booking.from }} to {{ booking.to }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="" class="text-muted">Select the star rating (1 is the worest - 5 is the
                                        best)</label>
                                    <review-stars @reviewChanged="review.rating = $event" class="fa-3x"
                                        :rating="review.rating"></review-stars>
                                </div>
                                <div class="form-group">
                                    <label for="content" class="text-muted">Describe your experience</label>
                                    <textarea name="content" v-model="review.content" cols="30" rows="10"
                                        class="form-control" :class="{ 'is-invalid': storeErrors }"></textarea>
                                </div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-md btn-primary mt-2" @click="storeReview">Submit</button>
                                </div>
                                <div class="alert alert-danger mt-4" v-if="storeErrors">
                                    {{ this.storeErrors['content'] }}
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!-- <div class="col-md-12 error-style mt-4" v-if="noBookingError">
                <h2>Review Not Available, No Such Booking Found</h2>
            </div> -->

            <error v-if="noBookingError">
                    <i class="fa fa-bomb"></i>
                    <h1 class="mt-4">Warning!</h1>
                    <h2>Review Not Available, No Such Booking Found</h2>
                </error>

            <!-- <div class="col-md-12" v-if="unknownError">Unknown error has occured, please try again later</div> -->
            <fatal-error v-if="unknownError"></fatal-error>
        </div>
    </div>


    <!-- Old Code for video the above is mine
        
        <div class="row">

        <div :class="[{ 'col-md-4': loading || hasBooking }, { 'd-none': !loading && !hasBooking }]">
            <div class="card">
                <div class="card-body">
                    <div v-if="!loading && hasBooking">
                        <p>
                            Stayed at
                            <router-link :to="{ name: 'showbookable', params: { id: booking.bookable.id } }">
                                {{ booking.bookable.title }}
                            </router-link>
                        </p>
                        <p>From {{ booking.from }} to {{ booking.to }}</p>

                    </div>
                    <div v-else>Loading...</div>
                </div>
            </div>
        </div>


        <div :class="[{ 'col-md-8': loading || !hasReview }, { 'col-12': !loading && hasReview }]">
            <div v-if="loading">Loading....</div>
            <div v-else>
                <div v-if="hasReview">You already left a review for this booking</div>
                <div v-if="!hasReview && hasBooking">
                    <div class="form-group mb-3">
                        <label for="" class="text-muted">Select the star rating (1 is the worest - 5 is the
                            best)</label>
                        <review-stars @reviewChanged="review.rating = $event" class="fa-3x"
                            :rating="review.rating"></review-stars>
                    </div>
                    <div class="form-group">
                        <label for="content" class="text-muted">Describe your experience</label>
                        <textarea name="content" v-model="review.content" cols="30" rows="10"
                            class="form-control"></textarea>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-md btn-primary mt-2">Submit</button>
                    </div>
                </div>
                <div v-if="!hasReview && !hasBooking">Review Not Available, No Such Booking</div>
            </div>
        </div>
    </div> -->
</template>
<script>
import { isLoggedIn } from '../../shared/auth';
export default {
    data() {
        return {
            review: {
                id: this.$route.params.id,
                rating: 5,
                content: null,
                user_id: null
            },
            existingReview: null,
            loading: false,
            booking: null,
            storeErrors: null,
            unknownError: false,
            noBookingError: false,
            reviewAttempted: false
        }
    },
    created() {
        this.loading = true;
        axios.get(`/api/reviews/${this.$route.params.id}`)
            .then(response => {
                this.existingReview = response.data.data;
            })
            .catch(error => {
                if (error.response && error.response.status && error.response.status == 404) {
                    return axios.get(`/api/booking-by-review/${this.$route.params.id}`)
                        .then(response => {
                            this.booking = response.data.data;
                        }).catch(error => {
                            //as 404 represent no booking 
                            if (error.response && error.response.status && error.response.status == 404) {
                                this.noBookingError = true;
                            } else {
                                this.unknownError = true;
                            }
                        })
                }
            }).then(() => {
                this.loading = false;
            })
    },
    computed: {
        // alreadyReviewed() {
        //     return this.hasReview && this.booking == null;
        // },
        hasReview() {
            return this.existingReview != null;
        },
        hasBooking() {
            return this.booking != null;
        },
        reviewStored() {
            return !this.loading && this.reviewAttempted;
        }
    },
    methods: {
        storeReview() {
            this.reviewAttempted = false;
            this.review.user_id = isLoggedIn() ? this.$store.state.user.id : null;
            axios.post(`/api/reviews/`, this.review)
                .then(response => {
                    console.log(response);
                    this.reviewAttempted = true;
                    setTimeout(()=>{
                        this.$router.push({name: 'profile'});
                    }, 4000);
                }).catch(error => {
                    if (422 === error.response.status) {
                        this.storeErrors = error.response.data.errors;
                    } else {

                        this.unknownError = true;
                    }
                });

        }
    }
}
</script>
<style scoped>
.error-style {
    color: #999;
    font-size: 10rem;
}
</style>
