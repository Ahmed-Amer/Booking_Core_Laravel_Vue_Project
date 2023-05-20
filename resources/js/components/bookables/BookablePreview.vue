<template>
  <div class="row">
    <div class="col-md-8 pb-4">
      <div class="card">
        <div class="card-body">
          <div v-if="!loading">
            <h2>{{ bookable.title }}</h2>
            <hr />
            <article>{{ bookable.description }}</article>
          </div>
          <div v-else>Loading...</div>
        </div>
      </div>
      <review-list :bookableId="bookableId"></review-list>
    </div>
    <div class="col-md-4 pb-4">
      <availability :bookableId="bookableId" @available="checkPrice($event)" class="mb-4">
      </availability>
      <transition name="fade">
        <price-breakdown v-if="price" :price="price" class="mb-4"></price-breakdown>
      </transition>
      <transition name="fade">
        <div class="d-grid gap-2" v-if="price">
          <buttton class="btn btn-sm btn-block btn-outline-secondary" @click="addToBasket">Book
            Now</buttton>
        </div>
      </transition>
      <transition name="fade">
        <div class="d-grid gap-2" v-if="alreadyInBasketFromGetters">
          <buttton class="btn btn-sm btn-block btn-outline-secondary mt-2" @click="removeFromBasket">Remove from 
            basket</buttton>
        </div>
      </transition>

      <div v-if="addToBasketError" class="mt-4 text-body-secondary warning">Seems like you've already added this object to basket.
         If you want to change dates, remove it first</div>
    </div>
  </div>
</template>

<script>
import Availability from './Availability.vue';
import ReviewList from '../reviews/ReviewList.vue';
import PriceBreakdown from './PriceBreakDown.vue';

import { mapState, mapGetters } from 'vuex'

export default
  {
    components: {
      Availability,
      ReviewList,
      PriceBreakdown
    },
    data() {
      return {
        bookableId: this.$route.params.id,
        bookable: null,
        loading: false,
        price: null,
        addToBasketError : false
      };
    },
    watch: {
      '$route.params.id': {
        handler: function (value) {
          this.bookableId = value;
          //alert('route changed ' + this.bookableId);
        },
        deep: true,
        immediate: true,
      }
    },
    created() {
      this.loading = true;
      axios.get("/api/bookables/" + this.$route.params.id).then(response => {
        this.bookable = response.data;
        this.loading = false;
      });
    },
    methods: {
      checkPrice(hasAvailability) {
        if (!hasAvailability) {
          this.price = null;
          return;
        } else {
          axios.get(
            `/api/bookables/${this.bookableId}/price?from=${this.lastSearch.from}&to=${this.lastSearch.to}`
          )
            .then(response => {
              this.price = response.data.data;
              //console.log(this.price);
            })
        }
      },
      isEqual(fromDate, toDate) {
        return fromDate.valueOf() == toDate.valueOf();
      },
      addToBasket() {

        let payload = {
          bookable: this.bookable,
          price: this.price,
          dates: this.lastSearch
        };

        //check if basket already have the booking 
        if(!this.alreadyInBasketFromGetters){
          this.$store.dispatch('addToBasket', payload);
        }else{
          this.addToBasketError = true;
        }

      },
      removeFromBasket(){
        this.$store.dispatch('removeFromBasket' , this.bookable.id);
        this.addToBasketError = false;
      }

    },
    computed: {
      ...mapState({
        lastSearch: 'lastSearch'
      }),
      ...mapGetters({
        itemsInBasket: 'itemsInBasket',
        itemsLength: 'itemsLength'
      }),
      alreadyInBasketFromGetters(){
        if(this.bookable == null){
          return false;
        }

        //using filter which return array of matching
        // let basketItem = this.itemsInBasket.filter(item =>
        //   item.bookable.id == this.bookable.id
        //   );

        //check if the searched element exists at the result array
        // if (basketItem.length == 0) {
        //   return false;
        // }
        // return true;

        //using reduce (called from store getters)
        return this.$store.getters.alreadyInBasket(this.bookable.id);
      }
     
    }
  }
</script>
<style scoped>
.warning{
  font-size: 0.7rem;
  color : red;
}
</style>