<template>
  <div id="app">
    <login v-if="user_id === null" @logged_in="this.logged_in"/>
  </div>
</template>

<script>
import Login from './components/Login.vue'

export default {
  name: 'App',
  components: {
    Login
  },

  data() {
    return {
      user_id: null
    }
  },

  methods: {
    logged_in(user) {
      console.log(user);
      this.user_id = user.id;
    }
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  width: 100vw;
  height: 100vh;
  margin: 0;
}
</style>
