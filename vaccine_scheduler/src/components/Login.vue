<template>
  <div id="login">
    <img class="background"/>
    <div id="login_card">
      <h1 id="login_title">Schedule your Covid-19 vaccine today!</h1>
      <br/>

      <div id="create_div">
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
              v-model="create_form.email"
              type="email"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-2" label="Password" label-for="create-2" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-2" type="password" v-model="create_form.password" aria-describedby="password-help-block" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-3" label="Confirm Password" label-for="create-3" :invalid-feedback="invalidFeedback" :state="equal_state" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-3" type="password" v-model="create_form.password2" aria-describedby="password-help-block" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-4" label="First Name" label-for="create-4" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-4" type="text" v-model="create_form.first_name" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-5" label="Last Name" label-for="create-5" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-5" type="text" v-model="create_form.last_name" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-6" label="Middle Initial" label-for="create-6" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-6" type="text" v-model="create_form.middle_initial" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-7" label="Date of Birth" label-for="create-7" label-cols-sm="4" content-cols-lg="7">
            <b-form-datepicker id="create-7" v-model="create_form.birth_date" class="mb-2" required></b-form-datepicker>
          </b-form-group>

          <b-form-group id="create-group-8" label="Phone Number" label-for="create-8" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-8" type="number" v-model="create_form.phone_number" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-9" label="Address" label-for="create-9" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-9" type="text" v-model="create_form.address.street_address" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-10" label="City" label-for="create-10" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-10" type="text" v-model="create_form.address.city" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-11" label="State" label-for="create-11" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-11" type="text" v-model="create_form.address.state" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-12" label="Zipcode" label-for="create-12" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-12" type="text" v-model="create_form.address.zipcode" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-13" label="Sex" v-slot="{ ariaDescribedby }" label-cols-sm="4" content-cols-lg="7">
            <b-form-radio v-model="selected" :aria-describedby="ariaDescribedby" name="some-radios" value="0">Male</b-form-radio>
            <b-form-radio v-model="selected" :aria-describedby="ariaDescribedby" name="some-radios" value="1">Female</b-form-radio>
          </b-form-group>

          <b-form-group id="create-group-14" label="Social Security Number:" label-for="create-14" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-14" type="number" v-model="create_form.SSN" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-15" label="Medical History:" label-for="create-15" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-15" type="text" v-model="create_form.medical_history" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-16" label="Occupation Class:" label-for="create-16" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-16" type="text" v-model="create_form.occupation_class" required></b-form-input>
          </b-form-group>

          <b-form-group id="create-group-17" label="Race:" label-for="create-17" label-cols-sm="4" content-cols-lg="7">
            <b-form-input id="create-17" type="text" v-model="create_form.race" required></b-form-input>
          </b-form-group>

          <b-button type="submit" id="loginbtn2" variant="primary">Create Account</b-button>
        </b-form>
      </div>
    </div>

    <div id="login_card2">
      <div id="login_div">
        <b-form @submit="onSubmit($event)">
          <b-form-group
            id="input-group-1"
            label="Email address:"
            label-for="input-1"
          >
            <b-form-input
              id="input-1"
              v-model="login.email"
              type="email"
              required
            ></b-form-input>
          </b-form-group>

          <b-form-group id="input-group-2" label="Password:" label-for="input-2">
            <b-form-input id="input-2" type="password" v-model="login.password" aria-describedby="password-help-block" required></b-form-input>
          </b-form-group>
      
          <b-button type="submit" id="loginbtn" variant="primary">Login</b-button>
        </b-form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
      login: {
        email: "",
        password: ""
      },

      create_form: {
        email: "peterheald8@gmail.com",
        password: "password123",
        password2: "password123",
        first_name: "Peter",
        last_name: "Heald",
        middle_initial: "G",
        birth_date: "2000-01-01",
        phone_number: "6209392099",
        address: {
          street_address: "1234 Lake Avenue",
          city: "Chicago",
          state: "IL",
          zipcode: "60504"
        },
        sex: "0",
        SSN: "620987654",
        medical_history: "I am a ghost",
        occupation_class: "Dying",
        race: "Dead"
      },

      user_id: null,
      user_info: {}
      
    }
  },

  computed: {
    equal_state() {
      return this.create_form.password2 != "" && this.create_form.password2 == this.create_form.password;
    },

    invalidFeedback() {
      if (this.create_form.password2 != this.create_form.password) {
        return "Please re-enter the exact password";
      } else {
        return "Re-enter password";
      }
    },

    address() {
      return this.create_form.address.street_address + " " + this.create_form.address.city + ", " + this.create_form.address.state + " " + this.create_form.address.zipcode;
    }
  },

  methods: {
    onSubmit: async function(event) {
      if (event) {
        event.preventDefault()
      }
      console.log("HI");
      let logged_in = await this.loginReq(this.login.email, this.login.password);
      if (logged_in) {
        this.logged_in();
      }
      return false;
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

      let patient_created = await this.createPatient();
      if (patient_created) {
        let logged_in = await this.loginReq(this.create_form.email, this.create_form.password);
        if (logged_in) {
          this.logged_in();
        }
      }
      return false;
    },

    errorToast(err) {
      this.$bvToast.toast(err + "", {
        title: "Error",
        variant: "danger",
        solid: true,
        toaster: 'b-toaster-bottom-right'
      })
    },

    createUser() {
      let sql = `INSERT INTO Users (email, password, first_name, last_name, middle_initial, birth_date, phone_number, address, sex, user_type) VALUES ("${this.noApostraphe(this.create_form.email)}", "${this.noApostraphe(this.create_form.password)}", "${this.noApostraphe(this.create_form.first_name)}", "${this.noApostraphe(this.create_form.last_name)}", '${this.noApostraphe(this.create_form.middle_initial)}', '${this.noApostraphe(this.create_form.birth_date)}', "${this.noApostraphe(this.create_form.phone_number)}", "${this.noApostraphe(this.address)}", ${this.create_form.sex}, 2)`
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
          let sql = `SELECT id FROM Users WHERE email="${this.noApostraphe(this.create_form.email)}" AND password="${this.noApostraphe(this.create_form.password)}" LIMIT 1`;
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

    createPatient() {
      let sql = `INSERT INTO Patient (user_id, SSN, Medical_history, Occupation_class, Race) VALUES (${this.user_id}, ${this.create_form.SSN}, "${this.create_form.medical_history}", "${this.create_form.occupation_class}", "${this.create_form.race}")`;
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("Could not create patient.");
        } else {
          return true;
        }
      }).catch(err => { this.errorToast(err); return false; });
    },

    logged_in() {
      this.$emit('logged_in', this.user_info);
    },

    loginReq(email, password) {
      let sql = `SELECT * FROM Users WHERE email="${this.noApostraphe(email)}" AND password="${this.noApostraphe(password)}" LIMIT 1`;
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not retrieve user");
        } else {
          this.user_info = res.body.rows[0];
          return true;
          // if (this.user_info.user.id == "0") {
          //   return true;
          // } else {
          //   var sql2 = "";
          //   if (this.user_info.user.id == "1") {
          //     sql2 = `SELECT * FROM Nurse WHERE user_id=${this.user_info.user.id}`;
          //   } else if (this.user_info.user.id == "2") {
          //     sql2 = `SELECT * FROM Patient WHERE user_id=${this.user_info.user.id}`;
          //   }

          //   console.log(sql2);

          //   return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql2}).then(res => {
          //     console.log(res);
          //     if (res.body.status == "ERROR") {
          //       throw Error("could not retrieve user");
          //     } else {
          //       if (this.user_info.user.id == "1") {
          //         this.user_info["nurse"] = res.body.rows[0];
          //       } else if (this.user_info.user.id == "2") {
          //         this.user_info["patient"] = res.body.rows[0];
          //       }
          //       return true;
          //     }
          //   }).catch(err => { this.errorToast(err); return false; });

          // }
        }
      }).catch(err => { this.errorToast(err); return false; });
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

#loginbtn {
  margin-left: 1rem;
  background: transparent;
  color: #007bff;
}

#login {
  width: 100%;
  height: 100%;
}

#login_card {
  width: 30%;
  height: fit-content;
  position: relative;
  top: 0%;
  padding-right: 3rem;
  padding-top: 5%;
  padding-left: 5%;
  height: 100%;
  left: 0%;
  background: rgba(255,255,255,1);
  border-radius: 0rem;
  padding: 1rem;
  font-family: roboto;
}

#login_card2 {
  width: fit-content;
  height: fit-content;
  position: relative;
  right: 2%;
  position: absolute;
  top: 2%;
  background: rgba(255,255,255, 1);
  box-shadow: 0.5rem 0.5rem 3rem rgba(0,0,0,0.3);
  border-radius: 1rem;
  padding: 1rem;
  font-family: roboto;
}

#login_div {
  display: flex;
}

.background {
  background: url('../assets/login.jpg') no-repeat;
  background-size: cover;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  filter: blur(10px);
}

#login_div .form-group {
    margin-bottom: 1rem;
    display: inline-flex;
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

#login_title {
  font-family: roboto;
  font-weight: 300;
  text-align: left;
  /* text-decoration: underline; */
  color: black;
  /* text-shadow: 1px 1px 0.23rem black; */
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
