<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">
      Check Availability
      <transition name="fade">
        <span v-if="noAvailability" class="text-danger">(NOT AVAILABLE)</span>
      </transition>

      <transition name="fade">
        <span v-if="hasAvailability" class="text-success">(AVAILABLE)</span>
      </transition>
    </h6>

    <div class="row mb-3">
      <div class="form-group col-md-6">
        <label for="from">From</label>
        <input type="text" name="from" class="form-control form-control-sm" placeholder="Start date" v-model="from"
          @keyup.enter="check" :class="[{ 'is-invalid': this.errorFor('from') }]" />
        <div class="invalid-feedback" v-for="(error, index) in this.errorFor('from')" :key="'from' + index">{{ error }}
        </div>
      </div>

      <div class="form-group col-md-6">
        <label for="to">To</label>
        <input type="text" name="to" class="form-control form-control-sm" placeholder="End date" v-model="to"
          @keyup.enter="check" :class="[{ 'is-invalid': this.errorFor('to') }]" />
        <div class="invalid-feedback" v-for="(error, index) in this.errorFor('to')" :key="'to' + index">{{ error }}</div>
      </div>
    </div>
    <div class="d-grid gap-2">
      <button class="btn btn-sm btn-secondary" @click="check" :disabled="loading">
       <span v-if="!loading">Check!</span>
       <span v-if="loading"><i class="fas fa-spinner fa-spin"></i> Checking...</span>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    bookableId: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      from: this.$store.state.lastSearch.from,
      to: this.$store.state.lastSearch.to,
      loading: false,
      status: null,
      errors: null
    };
  },
  methods: {
    check() {
      this.loading = true;
      this.errors = null;
      this.$store.dispatch('setLastSearchAction', {
        from: this.from,
        to: this.to
      });
      axios
        .get(
          `/api/bookables/${this.bookableId}/availability?from=${this.from}&to=${this.to}`
        )
        .then(response => {
          this.status = response.status;
          this.$emit('available' , this.hasAvailability);
        })
        .catch(error => {
          if (422 === error.response.status) {
            this.errors = error.response.data.errors;
          }
          this.status = error.response.status;
          this.$emit('available' , this.hasAvailability);
        })
        .then(() => (this.loading = false));
    },
    errorFor(field) {
      return this.hasErrors && this.errors[field] ? this.errors[field] : null;
    }
  },
  computed: {
    hasErrors() {
      return 422 === this.status && this.errors !== null;
    },
    hasAvailability() {
      return 200 === this.status;
    },
    noAvailability() {
      return 404 === this.status;
    }
  }
};
</script>

<style scoped>
label {
  font-size: 0.7rem;
  text-transform: uppercase;
  color: gray;
  font-weight: bolder;
}
</style>