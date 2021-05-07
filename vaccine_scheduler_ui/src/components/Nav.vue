<template>
  <b-nav id="nav">
    <b-nav-item @click="open_user_info($event)">
        <b-icon icon="person-fill"></b-icon> {{welcomMessage}}
    </b-nav-item>
  </b-nav>
</template>

<script>
export default {
  name: 'Login',
  props: {
      user_info: Object,
      USER_TYPES: Object
  },
  data() {
    return {
      
    }
  },

  computed: {
      welcomMessage() {
          if (this.user_info.user_type == this.USER_TYPES["ADMIN"]) {
              return "Admin"
          } else {
              return this.user_info.first_name + " " + this.user_info.last_name;
          }
      }
  },

  methods: {
      open_user_info(e) {
          if (e) {
              e.preventDefault();
          }

          this.$emit('open_user_info');
      }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
@font-face {
  font-family: roboto;
  src: url('../assets/fonts/Roboto-Light.ttf');
}

@font-face {
  font-family: roboto_bold;
  src: url('../assets/fonts/Roboto-BoldCondensed.ttf');
}

#nav {
    box-shadow: 1rem 0rem 1rem rgb(0 0 0 / 20%);
    position: fixed;
    top: 0;
    width: 100vw;
    left: 0;
}
</style>
