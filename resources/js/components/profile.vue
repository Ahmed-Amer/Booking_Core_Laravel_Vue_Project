<template>
  <div v-if="loading">Data is loading...</div>
  <div v-else>
    <div class="row">
      <div class="col-md-6 mb-3" v-for="booking in userBookings" :key="booking.id">
        <div class="card">
          <div class="card-body">
            <p>
              Stayed at
              <router-link style="color:#0d6efd;" :to="{ name: 'showbookable', params: { id: booking.bookable_id } }">
                {{ booking.title }}
              </router-link>
            </p>
            <p>From {{ booking.from }} to {{ booking.to }}</p>
            <button :disabled="!booking.review_key" class="btn btn-secondary btn-block">
              <router-link :to="'/review/' + booking.review_key">
                <span v-if="booking.review_key">add review</span>
                <span v-else>Already reviewed</span>
              </router-link>
            </button>


          </div>
        </div>
      </div>
      <div v-if="userBookings.length == 0">No bookings Available..</div>

    </div>
  </div>
</template>

<script>
import { isLoggedIn } from '../shared/auth';

export default {
  data() {
    return {
      loading: false,
      userBookings: null,
      alreadyReviewed: false
    }
  },
  computed: {

  },
  methods: {

  },
  async created() {
    await this.$store.dispatch('loadUser');
    this.loading = true;
    axios.get(`/api/booking-by-userid/${this.$store.state.user.id}`)
      .then(response => {
        this.userBookings = response.data;
      }).then(() => {
        this.loading = false;
      });
  },
beforeRouteEnter(to , from ,next){
      if(isLoggedIn())
      {
        next();
      }else{
        
        next({ name: 'login' });
      }
    }

}
</script>


<style scoped></style>