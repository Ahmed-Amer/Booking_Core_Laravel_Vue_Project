import { store } from '../store';


export function isLoggedIn(){
    return localStorage.getItem('isLoggedIn') == 'true';
}

export function logIn(){
    localStorage.setItem('isLoggedIn' , true);
}

export function logOut(){
    localStorage.setItem('isLoggedIn' , false);
}

export function checkAdmin(){
    return store.state.isAdmin == true;
}