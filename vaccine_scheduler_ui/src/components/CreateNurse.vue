<template>
    <div id="create_nurse_form">
        <b-button id="close" size="lg" @click="close()"><b-icon icon="x-circle"></b-icon></b-button>
        <div id="form">
            <b-form @submit="onSubmit2($event)">
                <b-form-group
                id="create-group-1"
                label="Email address:"
                label-for="create-1"
                label-cols-sm="4"
                content-cols-lg="7"
                content-cols-sm
                >
                <b-form-input
                    id="create-1"
                    v-model="user_form.email"
                    type="email"
                    required
                ></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-2" label="Password" label-for="create-2" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="create-2" type="password" v-model="user_form.password" aria-describedby="password-help-block" required></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-3" label="Confirm Password" label-for="create-3" :invalid-feedback="invalidFeedback" :state="equal_state" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="create-3" type="password" v-model="user_form.password2" aria-describedby="password-help-block" required></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-4" label="First Name" label-for="create-4" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="create-4" type="text" v-model="user_form.first_name" required></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-5" label="Last Name" label-for="create-5" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="create-5" type="text" v-model="user_form.last_name" required></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-6" label="Middle Initial" label-for="create-6" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="create-6" type="text" v-model="user_form.middle_initial" required></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-7" label="Date of Birth" label-for="create-7" label-cols-sm="4" content-cols-lg="7">
                <b-form-datepicker id="create-7" v-model="user_form.birth_date" class="mb-2" required></b-form-datepicker>
                </b-form-group>

                <b-form-group id="create-group-8" label="Phone Number" label-for="create-8" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="create-8" type="number" v-model="user_form.phone_number" required></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-9" label="Address" label-for="create-9" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="create-9" type="text" v-model="user_form.address" required></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-13" label="Sex" v-slot="{ ariaDescribedby }" label-cols-sm="4" content-cols-lg="7">
                <b-form-radio v-model="user_form.sex" :aria-describedby="ariaDescribedby" name="some-radios" value="0">Male</b-form-radio>
                <b-form-radio v-model="user_form.sex" :aria-describedby="ariaDescribedby" name="some-radios" value="1">Female</b-form-radio>
                </b-form-group>

                <b-button type="submit" id="createbtn" variant="success">Create Nurse</b-button>
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

  methods: {
    onSubmit2: async function(event) {
      if (event) {
        event.preventDefault()
      }
      console.log("HI2");
      let user_created = await this.createUser();
      console.log("USER CREATED: " + user_created);
      console.log(this.user_id);
      if (!user_created) {
        return false;
      }

      let nurse_created = await this.createNurse();
      if (nurse_created) {
        this.user_form = {
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
        };
        this.$bvToast.toast("Created Nurse", {
            title: "Success",
            variant: "success",
            solid: true,
            toaster: 'b-toaster-bottom-right'
        })
      }
      return false;
    },

    createUser() {
      let sql = `INSERT INTO Users (email, password, first_name, last_name, middle_initial, birth_date, phone_number, address, sex, user_type) VALUES ("${this.noApostraphe(this.user_form.email)}", "${this.noApostraphe(this.user_form.password)}", "${this.noApostraphe(this.user_form.first_name)}", "${this.noApostraphe(this.user_form.last_name)}", '${this.noApostraphe(this.user_form.middle_initial)}', '${this.noApostraphe(this.user_form.birth_date)}', "${this.noApostraphe(this.user_form.phone_number)}", "${this.noApostraphe(this.user_form.address)}", ${this.user_form.sex}, 1)`
      // let sql = `INSERT INTO Users (email, password, first_name, last_name, middle_initial, birth_date, phone_number, address, sex, user_type) VALUES ("admin", "password123", "John", "Apple", 'G', '2000-01-01', "6209392099", "1234 Lake Avenue Chicago, IL 60504", 0, 0);`
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          if (res.body.message.indexOf("Duplicate entry") != -1) {
            throw Error("There is already an account with this email. Please login with your password.");
          } else {
            throw Error("could not create user");
          }
        } else {
          let sql = `SELECT id FROM Users WHERE email="${this.noApostraphe(this.user_form.email)}" AND password="${this.noApostraphe(this.user_form.password)}" LIMIT 1`;
          return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
            console.log(res);
            if (res.body.status == "ERROR") {
              throw Error("could not retrieve user");
            } else {
              // this.$emit('logged_in', res.body.rows[0]);
              this.user_id = res.body.rows[0].id;
              return true;
            }
          }).catch(err => { this.errorToast(err); return false; });
        }

      }).catch(err => { this.errorToast(err); return false; });
    },

    createNurse() {
      let sql = `INSERT INTO Nurse (user_id) VALUES (${this.user_id})`;
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("Could not create nurse.");
        } else {
          return true;
        }
      }).catch(err => { this.errorToast(err); return false; });
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

#create_nurse_form {
    position: fixed;
    top: 0;
    width: 100vw;
    height: 100vh;
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