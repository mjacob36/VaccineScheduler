<template>
    <div id="user_info">
        <b-button id="close" size="lg" @click="close()"><b-icon icon="x-circle"></b-icon></b-button>
        <div id="form">
            <b-form @submit="onSubmit2($event)">
                <b-form-group
                id="form-group-1"
                label="Email address:"
                label-for="form-1"
                label-cols-sm="4"
                content-cols-lg="7"
                content-cols-sm
                >
                <b-form-input
                    id="form-1"
                    v-model="user_form.email"
                    type="email"
                    required
                ></b-form-input>
                </b-form-group>

                <b-form-group id="form-group-2" label="Password" label-for="form-2" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="form-2" type="password" v-model="user_form.password" aria-describedby="password-help-block" required></b-form-input>
                </b-form-group>

                <b-form-group id="form-group-3" label="Confirm Password" label-for="form-3" :invalid-feedback="invalidFeedback" :state="equal_state" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="form-3" type="password" v-model="user_form.password2" aria-describedby="password-help-block" required></b-form-input>
                </b-form-group>

                <b-form-group id="form-group-4" label="First Name" label-for="form-4" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="form-4" type="text" v-model="user_form.first_name" required></b-form-input>
                </b-form-group>

                <b-form-group id="form-group-5" label="Last Name" label-for="form-5" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="form-5" type="text" v-model="user_form.last_name" required></b-form-input>
                </b-form-group>

                <b-form-group id="form-group-6" label="Middle Initial" label-for="form-6" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="form-6" type="text" v-model="user_form.middle_initial" required></b-form-input>
                </b-form-group>

                <b-form-group id="form-group-7" label="Date of Birth" label-for="form-7" label-cols-sm="4" content-cols-lg="7">
                <b-form-datepicker id="form-7" v-model="user_form.birth_date" class="mb-2" required></b-form-datepicker>
                </b-form-group>

                <b-form-group id="form-group-8" label="Phone Number" label-for="form-8" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="form-8" type="number" v-model="user_form.phone_number" required></b-form-input>
                </b-form-group>

                <b-form-group id="form-group-9" label="Address" label-for="form-9" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="form-9" type="text" v-model="user_form.address" required></b-form-input>
                </b-form-group>

                <b-form-group id="form-group-13" label="Sex" v-slot="{ ariaDescribedby }" label-cols-sm="4" content-cols-lg="7">
                <b-form-radio v-model="user_form.sex" :aria-describedby="ariaDescribedby" name="some-radios" value="0">Male</b-form-radio>
                <b-form-radio v-model="user_form.sex" :aria-describedby="ariaDescribedby" name="some-radios" value="1">Female</b-form-radio>
                </b-form-group>
                
                <div v-if="user_info.user_type == USER_TYPES['PATIENT']">
                    <b-form-group id="form-group-14" label="Social Security Number:" label-for="form-14" label-cols-sm="4" content-cols-lg="7">
                    <b-form-input id="form-14" type="number" v-model="user_form.SSN" required></b-form-input>
                    </b-form-group>

                    <b-form-group id="form-group-15" label="Medical History:" label-for="form-15" label-cols-sm="4" content-cols-lg="7">
                    <b-form-input id="form-15" type="text" v-model="user_form.medical_history" required></b-form-input>
                    </b-form-group>

                    <b-form-group id="form-group-16" label="Occupation Class:" label-for="form-16" label-cols-sm="4" content-cols-lg="7">
                    <b-form-input id="form-16" type="text" v-model="user_form.occupation_class" required></b-form-input>
                    </b-form-group>

                    <b-form-group id="form-group-17" label="Race:" label-for="form-17" label-cols-sm="4" content-cols-lg="7">
                    <b-form-input id="form-17" type="text" v-model="user_form.race" required></b-form-input>
                    </b-form-group>
                </div>

                <b-button type="submit" id="loginbtn2" variant="primary">Save Changes</b-button>
            </b-form>
        </div>
    </div>
</template>

<script>
export default {
  name: 'UserInfo',
  props: {
      user_info: Object,
      USER_TYPES: Object
  },
  data() {
    return {
      user_form: {
        email: "",
        password: "",
        password2: "",
        first_name: "",
        last_name: "",
        middle_initial: "",
        birth_date: "",
        phone_number: "",
        address: "",
        sex: "",
        SSN: "",
        medical_history: "",
        occupation_class: "",
        race: ""
      }
    }
  },

  computed: {
    invalidFeedback() {
        if (this.user_form.password2 != this.user_form.password) {
            return "Please re-enter the exact password";
        } else {
            return "Re-enter password";
        }
    },

    equal_state() {
      return this.user_form.password2 != "" && this.user_form.password2 == this.user_form.password;
    },
  },

  created() {
      this.user_form.email = this.user_info.email;
      this.user_form.password = this.user_info.password;
      this.user_form.password2 = this.user_info.password;
      this.user_form.first_name = this.user_info.first_name;
      this.user_form.last_name = this.user_info.last_name;
      this.user_form.middle_initial = this.user_info.middle_initial;
      this.user_form.birth_date = this.user_info.birth_date;
      this.user_form.phone_number = this.user_info.phone_number;
      this.user_form.address = this.user_info.address;
      this.user_form.sex = this.user_info.sex;

      if (this.user_info.user_type == this.USER_TYPES["PATIENT"]) {
          console.log("HERE");
          this.user_form.SSN = this.user_info.patient.SSN;
          this.user_form.medical_history = this.user_info.patient.Medical_history;
          this.user_form.occupation_class = this.user_info.patient.Occupation_class;
          this.user_form.race = this.user_info.patient.Race;
      }
  },

  methods: {
    onSubmit2: async function(event) {
      if (event) {
        event.preventDefault()
      }
      console.log("HI2");
      var updated = await this.updateUserInfo();
      if (this.user_info.user_type == this.USER_TYPES["PATIENT"]) {
          updated = await this.updatePatientInfo();
      }

      if (updated) {
        this.$emit('update_user_info', this.user_info);
      }
      
      return false;
    },

    close() {
        this.$emit('close');
    },

    doubleApostraphe(str) {
      str = str.replace("'", "''");
      str = str.replace('"', '""');
      return str;
    },

    noApostraphe(str) {
      str = str.replace("'", "");
      str = str.replace('"', '');
      return str;
    },

    updateUserInfo() {
      let sql = `UPDATE Users SET email="${this.noApostraphe(this.user_form.email)}", password="${this.noApostraphe(this.user_form.password)}", first_name="${this.noApostraphe(this.user_form.first_name)}", last_name="${this.noApostraphe(this.user_form.last_name)}", middle_initial='${this.noApostraphe(this.user_form.middle_initial)}', birth_date='${this.noApostraphe(this.user_form.birth_date)}', phone_number="${this.noApostraphe(this.user_form.phone_number)}", address="${this.noApostraphe(this.user_form.address)}", sex=${this.user_form.sex} WHERE id=${this.user_info.id}`
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not update user info");
        } else {
            this.user_info.email = this.user_form.email;
            this.user_info.password = this.user_form.password;
            this.user_info.first_name = this.user_form.first_name;
            this.user_info.email = this.user_form.first_name;
            this.user_info.last_name = this.user_form.last_name;
            this.user_info.middle_initial = this.user_form.middle_initial;
            this.user_info.birth_date = this.user_form.birth_date;
            this.user_info.phone_number = this.user_form.phone_number;
            this.user_info.address = this.user_form.address;
            this.user_info.sex = this.user_form.sex;
            return true;
        }

      }).catch(err => { this.errorToast(err); return false; });
    },

    updatePatientInfo() {
      let sql = `UPDATE Patient SET SSN=${this.noApostraphe(this.user_form.SSN)}, Medical_history="${this.noApostraphe(this.user_form.medical_history)}", Occupation_class="${this.noApostraphe(this.user_form.occupation_class)}", Race="${this.noApostraphe(this.user_form.race)}" WHERE user_id=${this.user_info.id}`
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not update user info");
        } else {
            this.user_info.patient.SSN = this.user_form.SSN;
            this.user_info.patient.Medical_history = this.user_form.medical_history;
            this.user_info.patient.Occupation_class = this.user_form.occupation_class;
            this.user_info.patient.Race = this.user_form.race;
            return true;
        }

      }).catch(err => { this.errorToast(err); return false; });
    },

    errorToast(err) {
      this.$bvToast.toast(err + "", {
        title: "Error",
        variant: "danger",
        solid: true,
        toaster: 'b-toaster-bottom-right'
      })
    },
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

#user_info {
    position: fixed;
    width: 100vw;
    height: 100vh;
    top: 0;
    background: white;
}

#form {
    width: 40%;
    height: 100%;
    margin: auto;
    background: white;
    margin-top: 5vh;
    padding: 1rem;
}

input {
  background: transparent;
  border: none;
  border-bottom: 2px rgba(0,0,0, 0.3) solid;
  border-radius: 0;
  transition: 0.5s;
}

input:focus {
  box-shadow: none;
  outline: 0;
  background: transparent;
  border-bottom-color: black;
}

#close {
    border-radius: 2rem;
    position: fixed;
    top: 5%;
    height: auto;
    right: 3%;
    background: none;
    border: none;
    color: #EC8383;
}
</style>

<style>
label {
    display: inline-block;
    vertical-align: center;
    margin: auto;
    margin-right: 1rem;
    margin-left: 1rem;
}
</style>