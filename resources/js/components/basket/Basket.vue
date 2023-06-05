<template>
    <div>
        <success v-if="bookingStored">
            <i class="fa fa-thumbs-up"></i>
            <h1 class="mt-4">Success!</h1>
            <h2>Congratulation on your purchase!</h2>
            <p style="font-size: 0.8rem;">You will be redirected to home page in 4 seconds</p>
        </success>
        <div class="row" v-else>
            <div class="col-md-8" v-if="itemsLength != 0">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control" :class="[{ 'is-invalid': errorFor('customer.first_name') }]"
                            name="firstname" v-model="customer.first_name">
                        <span class="form-error">{{ errorFor('customer.first_name') }}</span>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control" :class="[{ 'is-invalid': errorFor('customer.last_name') }]"
                            name="lastname" v-model="customer.last_name">
                        <span class="form-error">{{ errorFor('customer.last_name') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" :class="[{ 'is-invalid': errorFor('customer.email') }]"
                            name="email" v-model="customer.email">
                        <span class="form-error">{{ errorFor('customer.email') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="street">Street</label>
                        <input type="text" class="form-control" :class="[{ 'is-invalid': errorFor('customer.street') }]"
                            name="street" v-model="customer.street">
                        <span class="form-error">{{ errorFor('customer.street') }}</span>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="city">City</label>
                        <input type="text" class="form-control" :class="[{ 'is-invalid': errorFor('customer.city') }]"
                            name="city" v-model="customer.city">
                        <span class="form-error">{{ errorFor('customer.city') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="country">Country</label>
                        <input type="text" class="form-control" :class="[{ 'is-invalid': errorFor('customer.country') }]"
                            name="country" v-model="customer.country">
                        <span class="form-error">{{ errorFor('customer.country') }}</span>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="state">State</label>
                        <input type="text" class="form-control" :class="[{ 'is-invalid': errorFor('customer.state') }]"
                            name="state" v-model="customer.state">
                        <span class="form-error">{{ errorFor('customer.state') }}</span>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="zip">Zip</label>
                        <input type="text" class="form-control" :class="[{ 'is-invalid': errorFor('customer.zip') }]"
                            name="zip" v-model="customer.zip">
                        <span class="form-error">{{ errorFor('customer.zip') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <div class="d-grid gap-2">
                            <!-- <buttton class="btn btn-md btn-primary mt-2" @click="saveBooking">
                                Book Now
                            </buttton> -->
                            <!-- Set up a container element for the button -->
                            <div class="mx-auto w-100 paypal" ref="paypal"></div>

                            <div v-if="loadingPaypal" >
                                <div class="text-center">
                                    <h5 class="text-center">Checking your payment please wait!</h5>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8" v-if="itemsLength == 0">
                <div class="text-body-secondary warning">Seems like your basket is empty</div>
            </div>
            <div class="col-md-4">
                <div class="d-flex justify-content-between">
                    <h6 class="text-uppercase text-secondary font-weight-bolder">Your Cart</h6>
                    <h6 class=" badge bg-secondary text-uppercase">
                        <span v-if="itemsLength">Items {{ itemsLength }}</span>
                        <span v-else>Empty</span>
                    </h6>
                </div>
                <transition-group name="fade">
                    <div v-for="item in itemsInBasket" :key="item.bookable.id">
                        <div class="pt-2 pb-2 border-top d-flex justify-content-between">
                            <span>
                                <router-link :to="{ name: 'showbookable', params: { id: item.bookable.id } }">
                                    {{ item.bookable.title }}
                                </router-link>
                            </span>
                            <span class="font-weight-bold"> $ {{ item.price.total_price }}</span>
                        </div>
                        <div class="pt-2 pb-2 border-top d-flex justify-content-between">
                            <span> From {{ item.dates.from }}</span>
                            <span> To {{ item.dates.to }}</span>
                        </div>
                        <div class="pt-2 pb-2 text-right">
                            <buttton class="btn btn-sm btn-outline-secondary" @click="trashElement(item.bookable.id)">
                                <i class="fa fa-trash-alt"></i>
                            </buttton>
                        </div>
                    </div>
                </transition-group>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { isLoggedIn } from '../../shared/auth';
export default {
    data() {
        return {
            customer: {
                first_name: null,
                last_name: null,
                email: null,
                street: null,
                city: null,
                country: null,
                state: null,
                zip: null,
            },
            auth_user: {
                id: null
            },
            errors: null,
            loading: false,
            bookingAttempted: false,
            total_paypal_payments: 0,
            loadingPaypal: false
        }
    },
    computed: {
        ...mapGetters({
            itemsInBasket: 'itemsInBasket',
            itemsLength: 'itemsLength',
        }),
        bookingStored() {
            return !this.loading && this.itemsLength == 0 && this.bookingAttempted;
        }
    },
    mounted() {
        const script = document.createElement("script");
        script.src =
            "https://www.paypal.com/sdk/js?client-id=AXjGtYlCjYelqwlsnrk_PwFX6BoH6uiq2Rtx24B3qCXwcG-9l7XUi8-ROKXLmuEqy9VDSzAJdWcFGcFB";
        script.addEventListener("load", this.setLoaded);
        document.body.appendChild(script);

        //calculate total payments
        if (this.itemsInBasket) {
            this.itemsInBasket.forEach((item , index) => {
                this.total_paypal_payments = this.total_paypal_payments + item['price']['total_price'];
            });
        }

    },
    methods: {
        setLoaded() {
            this.loadingPaypal = false;
            window.paypal
                .Buttons({
                    createOrder: (data, actions) => {
                        return actions.order.create({
                            purchase_units: [
                                {
                                    description: "HotelBnb System Booking",
                                    amount: {
                                        currency_code: "USD",
                                        value: this.total_paypal_payments
                                    }
                                }
                            ]
                        });
                    },
                    onApprove: async (data, actions, resp) => {
                        this.loadingPaypal = true;
                        const order = await actions.order.capture();
                        this.data;
                        this.loadingPaypal = false;
                        this.saveBooking();
                        // window.location.href = "./paymentsuccess/" + this.resp;
                    },
                    onError: err => {
                        console.log(err);
                    }
                })
                .render(this.$refs.paypal);
        },

        trashElement(id) {
            this.$store.dispatch('removeFromBasket', id);
        },
        saveBooking() {
            this.loading = true;
            this.errors = null;
            this.bookingAttempted = false;
            this.auth_user.id = isLoggedIn() ? this.$store.state.user.id : null;
            axios
                .post(`/api/checkout`, {
                    "customer": this.customer,
                    "auth_user_id": this.auth_user.id,
                    "bookings": this.itemsInBasket.map(function (item) {
                        return {
                            "bookable_id": item.bookable.id,
                            "from": item.dates.from,
                            "to": item.dates.to,
                            'total_price': item.price.total_price
                        }
                    })
                })
                .then(response => {
                    this.loading = false;
                    this.bookingAttempted = true;
                    this.$store.dispatch('clearBasket');
                    setTimeout(()=>{
                        this.$router.push({name: 'home'});
                    }, 4000);
                    
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
                .then(() => {

                });
        },
        errorFor(field) {
            return this.errors != null && this.errors[field] ? this.errors[field][0] : null;
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
.paypal{
    display: block !important;
    margin-top: 30px;
}
</style>