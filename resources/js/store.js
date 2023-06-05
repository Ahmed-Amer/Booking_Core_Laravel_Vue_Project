import { createStore } from 'vuex'
import { isLoggedIn, logOut } from './shared/auth';

/*you can use the known syntax of function or use arrow functions like getters*/
// Create a new store instance.
export const store = createStore({
  state() {
    return {
      lastSearch: {
        from: null,
        to: null
      },
      basket: {
        items: [],
      },
      isLoggedIn: false,
      user: {},
      isAdmin : false
    }
  },
  getters: {
    itemsInBasket: (state) => {
      return state.basket.items;
    },
    itemsLength: (state) => {
      return state.basket.items.length;
    },
    alreadyInBasket: (state) => (id) => {
      return state.basket.items.reduce((result , item) => result || id == item.bookable.id , false)
    }
  },
  mutations: {
    setLastSearch(state, payload) {
      state.lastSearch = payload;
    },
    addToBasket(state, payload) {
      state.basket.items.push(payload);
    },
    removeFromBasket(state, payload) {
      state.basket.items = state.basket.items.filter(item => item.bookable.id != payload);
    },
    setBasket(state, payload){
      state.basket = payload;
    },
    setUser(state, payload){
      state.user = payload;
    },
    setLoggedIn(state, payload){
      state.isLoggedIn = payload;
    },
    setIsAdmin(state, payload){
      state.isAdmin = payload;
    }
  },
  actions: {
    setLastSearchAction(context, payload) {
      context.commit('setLastSearch', payload);
      localStorage.setItem('lastSearch', JSON.stringify(payload))
    },
    loadStoredState(context) {
       //get last search from local storage
      const lastSearch = localStorage.getItem('lastSearch');
      if (lastSearch) {
        context.commit('setLastSearch', JSON.parse(lastSearch))
      }
      //get basket items from local storage
      const basket = localStorage.getItem('basket');
      if (basket) {
        context.commit('setBasket', JSON.parse(basket))
      }

      //set loggedIn from local storage
      context.commit("setLoggedIn" , isLoggedIn());
    },
    addToBasket(context , payload){
      context.commit('addToBasket' , payload);
      localStorage.setItem('basket', JSON.stringify(context.state.basket));
    },
    removeFromBasket(context , payload){
      context.commit('removeFromBasket' , payload);
      localStorage.setItem('basket', JSON.stringify(context.state.basket));
    },
    clearBasket(context , payload){
      context.commit('setBasket' , { items : []});
      localStorage.setItem('basket', JSON.stringify(context.state.basket));
    },
    async loadUser(context){
      if(isLoggedIn()){
        try {
          const user =  (await axios.get('/user')).data;
          context.commit("setUser" , user);
          context.commit("setLoggedIn" , true);
          context.dispatch("checkAdmin" , user.role);
        } catch (error) {
          context.dispatch("logout");
        }
      }

    },
    async checkAdmin(context , role){
      if(role == '777'){
        context.commit("setIsAdmin" , true);
      }else{
        context.commit("setIsAdmin" , false);
      }
    },
    logout(context){
      context.commit("setUser" , {});
      context.commit("setLoggedIn" , false);
      context.commit("setIsAdmin" , false);
      logOut();
    }
  }
})