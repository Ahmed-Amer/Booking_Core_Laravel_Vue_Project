<template>
    <div class="review-list">
        <h6 class="text-uppercase text-secondary font-weight-bolder">Review List</h6>
        <div v-if="loading">Loading....</div>
        <div v-else>
            <div class="border-bottom d-none d-md-block" v-for="(review, index) in reviews" :key="index">
                <div class="row pt-4">
                    <div class="col-md-6">Ahmed Amer</div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <review-stars class="fa-lg" :rating="review.rating"></review-stars>
                    </div>
                </div>
                <div class="row">
                    <!-- <div class="col-md-12">{{ relativeDate(review.created_at) }}</div> -->
                    <div class="col-md-12">{{ review.created_at }}</div>
                </div>
                <div class="row pt-4 pb-4">
                    <div class="col-md-12">{{ review.content }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";

export default {
    props: {
        bookableId: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            loading: false,
            reviews: null,
        }
    },
    created() {
        this.loading = true;
        axios
            .get(
                `/api/bookables/${this.bookableId}/reviews`
            )
            .then(response => {
                this.reviews = response.data.data;
            })
            .then(() => {
                this.loading = false;
            })
    },
    computed: {
        relativeDate(value) {
            // return (v) => {
            //     return moment(value).startOf('day').fromNow(); 
            // }
            const postDatetime = moment(
                "Wed Aug 27 13:08:45 +0000 2008",
                "dddd, MMMM Do, h:mm:ss a, YYYY"
            );
            const now = moment();
            const timeAgo = now.diff(postDatetime, 'seconds');
            return timeAgo;
        }
    }
}
</script>

<style scoped>
.review-list {
    padding: 1.25rem;
}
</style>