import { createRouter, createWebHistory } from 'vue-router'
import Bookables from './components/bookables/Bookables.vue';
import ShowBookable from './components/bookables/BookablePreview.vue';
import ReviewAdd from './components/reviews/ReviewAdd.vue';
import Basket from './components/basket/Basket.vue';
import Login from './components/auth/Login.vue';
import Register from './components/auth/register.vue';
import Profile from './components/profile.vue';
import Dashboard from './components/admin/dashboard.vue';
import AddBookable from './components/admin/addBookable.vue';
import AddUser from './components/admin/addUser.vue';
import AddReport from './components/admin/addReport.vue';


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
        {'path' : '/admin/dashboard' , component : Dashboard , name : 'dashboard'},
        {'path' : '/admin/add-bookable' , component : AddBookable , name : 'addbookable'},
        {'path' : '/admin/add-user' , component : AddUser , name : 'adduser'},
        {'path' : '/admin/add-report' , component : AddReport , name : 'addreport'},
        {'path' : '/:pathMatch(.*)*' , component : Bookables}

    ]
})