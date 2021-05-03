<template>
  <div id="app">
    <my-nav v-if="user_info.id != null" :user_info="user_info" :USER_TYPES="USER_TYPES" @open_user_info="show_user_info=true"/>
    <login v-if="user_info.id == null" @logged_in="this.logged_in"/>
    <admin v-if="user_info.user_type != null && user_info.user_type == USER_TYPES['ADMIN']" :USER_TYPES="USER_TYPES" :user_info="user_info"/>

    <transition name="fade">
      <user-info-page v-if="show_user_info == true" :user_info="user_info" :USER_TYPES="USER_TYPES" @update_user_info="this.update_user_info" @close="show_user_info = false"/>
    </transition>
  </div>
</template>

<script>
import Login from './components/Login.vue'
import MyNav from './components/Nav.vue'
import UserInfoPage from './components/UserInfo.vue'
import Admin from './components/Admin.vue'

export default {
  name: 'App',
  components: {
    Login,
    MyNav,
    UserInfoPage,
    Admin
  },

  data() {
    return {
      user_info: {},
      USER_TYPES: {
        "ADMIN": 0,
        "NURSE": 1,
        "PATIENT": 2
      },
      show_user_info: false,
    }
  },

  methods: {
    logged_in: async function(user) {
      this.user_info = user;
      this.user_info.id = parseInt(user.id);
      this.user_info.user_type = parseInt(user.user_type);
      this.user_info.sex = parseInt(user.sex);

      if (this.user_info.user_type == this.USER_TYPES["PATIENT"]) {
        console.log("HEREBOY");
        await this.getPatientInfo(this.user_info.id);
      } else if (this.user_info.user_type == this.USER_TYPES["NURSE"]) {
        await this.getNurseInfo(this.user_info.id);
      }
      
      console.log(this.user_info);
    },

    update_user_info: async function(user) {
      this.user_info = user;
      console.log(this.user_info);

      this.$bvToast.toast("Updated user info", {
        title: "Success",
        variant: "success",
        solid: true,
        toaster: 'b-toaster-bottom-right'
      });
    },

    errorToast(err) {
      this.$bvToast.toast(err + "", {
        title: "Error",
        variant: "danger",
        solid: true,
        toaster: 'b-toaster-bottom-right'
      })
    },

    getPatientInfo(user_id) {
      let sql = `SELECT * FROM Patient WHERE user_id=${user_id} LIMIT 1`;
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR" || res.body.rows.length == 0) {
          throw Error("could not retrieve patient");
        } else {
          this.user_info["patient"] = res.body.rows[0];
          return true;
        }
      }).catch(err => { this.errorToast(err); return false; });
    },

    getNurseInfo(user_id) {
      let sql = `SELECT * FROM Nurse WHERE user_id=${user_id} LIMIT 1`;
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR" || res.body.rows.length == 0) {
          throw Error("could not retrieve nurse");
        } else {
          this.user_info["nurse"] = res.body.rows[0];
          return true;
        }
      }).catch(err => { this.errorToast(err); return false; });
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
  max-height: 100vh;
  max-width: 100vw;
  overflow: hidden;
  height: 100vh;
  margin: 0;
}


.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
