<template>
    <app-header></app-header>
    
    <!-- Begin page content -->
    <main class="flex-shrink-0">
        <div class="container" style="padding-top: 40px;">
            <router-view></router-view>
        </div>
    </main>
</template>

<script>
import Header from './Header.vue';
export default {
    components: {
        appHeader: Header
    },
  beforeCreate() {
    this.$store.dispatch('loadStoredState');
    this.$store.dispatch('loadUser');
  }
}
</script>
