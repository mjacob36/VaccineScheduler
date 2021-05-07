<template>
    <div id="create_vaccine_form">
        <b-button id="close" size="lg" @click="close()"><b-icon icon="x-circle"></b-icon></b-button>
        <div id="form">
            <b-form @submit="onSubmit2($event)">
                <b-form-group
                id="create-group-1"
                label="Name:"
                label-for="create-1"
                label-cols-sm="4"
                content-cols-lg="7"
                content-cols-sm
                >
                <b-form-input
                    id="create-1"
                    v-model="user_form.Name"
                    type="text"
                    required
                ></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-2" label="Description" label-for="create-2" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="create-2" type="text" v-model="user_form.Description" required></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-4" label="Company" label-for="create-4" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="create-4" type="text" v-model="user_form.Company" required></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-5" label="Number of doses" label-for="create-5" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="create-5" type="number" v-model="user_form.Number_of_doses" required></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-6" label="Available" label-for="create-6" label-cols-sm="4" content-cols-lg="7">
                <b-form-input id="create-6" type="number" v-model="user_form.Available" required></b-form-input>
                </b-form-group>

                <b-form-group id="create-group-7" label="Hold" label-for="create-7" label-cols-sm="4" content-cols-lg="7">
                    <b-form-input id="create-7" type="number" v-model="user_form.Hold" required></b-form-input>
                </b-form-group>

                <b-button type="submit" id="createbtn" variant="success">Create Vaccine</b-button>
            </b-form>
        </div>
    </div>
</template>

<script>
export default {
  name: 'CreateVaccine',
  data() {
    return {
      user_form: {
        Name: "",
        Description: "",
        Company: "",
        Number_of_doses: null,
        Available: null,
        Hold: null
      }
    }
  },

  methods: {
    onSubmit2: async function(event) {
      if (event) {
        event.preventDefault()
      }
      console.log("HI2");
      let vaccine_created = await this.createVaccine();
      if (vaccine_created) {
        this.user_form = {
            Name: "",
            Description: "",
            Company: "",
            Number_of_doses: null,
            Available: null,
            Hold: null
        };
        this.$bvToast.toast("Created Vaccine", {
            title: "Success",
            variant: "success",
            solid: true,
            toaster: 'b-toaster-bottom-right'
        })
      }
      return false;
    },

    createVaccine() {
      let sql = `INSERT INTO Vaccine (Name, Description, Company, Number_of_doses, Available, Hold) VALUES ("${this.user_form.Name}", "${this.user_form.Description}", "${this.user_form.Company}", ${this.user_form.Number_of_doses}, ${this.user_form.Available}, ${this.user_form.Hold})`;
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("Could not create vaccine");
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