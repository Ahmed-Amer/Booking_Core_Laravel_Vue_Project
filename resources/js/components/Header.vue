<template>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <router-link to="/" class="navbar-brand">HotelBnb</router-link>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
        aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item" v-if="isLoggedIn">
            <router-link :to="{name : 'profile'}" class="nav-link">
              <a>
                Profile
              </a>
            </router-link>
          </li>

        </ul>


        <ul class="navbar-nav d-flex">
          <li class="nav-item">
            <router-link to="/basket" activeClass="active" class="nav-link"><a>
                Basket
                <span v-if="itemsLength" class="badge text-bg-secondary"> {{ itemsLength }}</span>
              </a></router-link>
          </li>

          <li class="nav-item" v-if="!isLoggedIn">
            <router-link :to="{name : 'register'}" class="nav-link"><a>
                Register
              </a></router-link>
          </li>

          <li class="nav-item" v-if="!isLoggedIn">
            <router-link :to="{name : 'login'}" class="nav-link"><a>
                Sign-in
              </a></router-link>
          </li>

          <li class="nav-item" v-if="isLoggedIn">
            <a class="nav-link" href="#" @click.prevent="logout">
              Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
  
<script>
import { mapState, mapGetters } from 'vuex'

export default
  {

    computed: {
      ...mapState({
        lastSearch: 'lastSearch',
        isLoggedIn: 'isLoggedIn'
      }),
      ...mapGetters({
        itemsLength: 'itemsLength'
      })
    },
    methods:{
      async logout(){
        try {
          axios.post('/logout');
          this.$store.dispatch('logout');
        } catch (error) {
          this.$store.dispatch('logout');
        }
      }
    }
  }
</script>
  