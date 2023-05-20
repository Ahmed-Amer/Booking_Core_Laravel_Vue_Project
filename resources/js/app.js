/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import App from './components/App.vue';
import { router } from './routes';
import { store } from './store';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({
    extends: App,
   async beforeCreate() {
    //    await axios.get('/sanctum/csrf-cookie');

    //     await axios.post('/login' , {
    //         email : 'harrison.jerde@example.org',
    //         password : 'password'
    //     });

    //     await axios.get('/user');
     }, 
});

app.use(router);
app.use(store);

import FatalError from './components/FatalError.vue';
import Error from './components/Error.vue';
import Success from './components/Success.vue';
import ReviewStars from './components/reviews/ReviewStars.vue';

app.component('fatal-error', FatalError);
app.component('error', Error);
app.component('success', Success);
app.component('review-stars', ReviewStars);

app.mount('#app');
