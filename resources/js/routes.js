import { createRouter, createWebHistory } from 'vue-router'
import Bookables from './components/bookables/Bookables.vue';
import ShowBookable from './components/bookables/BookablePreview.vue';
import ReviewAdd from './components/reviews/ReviewAdd.vue';
import Basket from './components/basket/Basket.vue';
import Login from './components/auth/Login.vue';
import Register from './components/auth/register.vue';
import Profile from './components/profile.vue';

export const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {'path' : '/' , component : Bookables , name : 'home'},
        {'path' : '/bookable/:id' , component : ShowBookable , name : 'showbookable'},
        {'path' : '/review/:id' , component : ReviewAdd , name : 'addreview'},
        {'path' : '/basket' , component : Basket , name : 'basket'},
        {'path' : '/profile' , component : Profile , name : 'profile'},
        {'path' : '/auth/login' , component : Login , name : 'login'},
        {'path' : '/auth/register' , component : Register , name : 'register'},
        
    ]
})