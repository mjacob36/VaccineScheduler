<template>
    <div id="admin">
        <div v-if="current_view == VIEWS['menu']" id="menu">
            <h1>Select View</h1>
            <div id="options">
                <b-button variant="outline-primary" @click="current_view = VIEWS['nurse']">Nurse</b-button>
                <b-button variant="outline-primary" @click="current_view = VIEWS['patient']">Patient</b-button>
                <b-button variant="outline-primary" @click="current_view = VIEWS['vaccine']">Vaccine</b-button>
            </div>
        </div>

        <div v-if="current_view == VIEWS['nurse']" id="nurse_view" class="view">
            <transition name="fade">
                <create-nurse v-if="create_nurse == true" @close="createNurseClosed()"/>
            </transition>

            <transition name="fade">
                <div id="nurses_container" v-if="create_nurse == false">
                    <h1 style="color: rgba(0,0,0,0.7);">Nurses</h1>
                    <b-button v-if="create_nurse == false" variant="outline-success" class="create_nurse" @click="create_nurse = true;">Create Nurse</b-button>
                    <div id="nurses_card" class="view_card">
                        <b-list-group id="nurses_list" class="view_list">
                            <b-list-group-item href="#" v-for="(nurse, index) in nurses" :key="nurse.id" @click="current_nurse = index" :active="current_nurse == index">{{nurse.first_name + " " + nurse.last_name}}</b-list-group-item>
                        </b-list-group>

                        <div id="current_nurse_view" class="current_view_box">
                            <div id="form" v-if="current_nurse != null">
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
                                        v-model="nurses[current_nurse].email"
                                        type="email"
                                        required
                                    ></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-2" label="Password" label-for="create-2" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-2" type="text" v-model="nurses[current_nurse].password" required></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-4" label="First Name" label-for="create-4" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-4" type="text" v-model="nurses[current_nurse].first_name" required></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-5" label="Last Name" label-for="create-5" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-5" type="text" v-model="nurses[current_nurse].last_name" required></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-6" label="Middle Initial" label-for="create-6" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-6" type="text" v-model="nurses[current_nurse].middle_initial" required></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-7" label="Date of Birth" label-for="create-7" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-datepicker id="create-7" v-model="nurses[current_nurse].birth_date" class="mb-2" required></b-form-datepicker>
                                    </b-form-group>

                                    <b-form-group id="create-group-8" label="Phone Number" label-for="create-8" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-8" type="number" v-model="nurses[current_nurse].phone_number" disabled></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-9" label="Address" label-for="create-9" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-9" type="text" v-model="nurses[current_nurse].address" disabled></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-13" label="Sex" v-slot="{ ariaDescribedby }" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-radio v-model="nurses[current_nurse].sex" :aria-describedby="ariaDescribedby" name="some-radios" value="0">Male</b-form-radio>
                                    <b-form-radio v-model="nurses[current_nurse].sex" :aria-describedby="ariaDescribedby" name="some-radios" value="1">Female</b-form-radio>
                                    </b-form-group>

                                    <b-button type="submit" id="createbtn" variant="success">Save Changes</b-button>
                                    <b-button id="deleteBtn" variant="danger" style="margin-left: 1rem;" @click="deleteClick($event)">Delete Nurse</b-button>
                                </b-form>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>


        <div v-if="current_view == VIEWS['patient']" id="patient_view" class="view">
            <transition name="fade">
                <div id="patient_container">
                    <h1 style="color: rgba(0,0,0,0.7);">Patients</h1>
                    <div id="patients_card" class="view_card">
                        <b-list-group id="patients_list" class="view_list">
                            <b-list-group-item href="#" v-for="(patient, index) in patients" :key="patient.id" @click="current_patient = index" :active="current_patient == index">{{patient.first_name + " " + patient.last_name}}</b-list-group-item>
                        </b-list-group>

                        <div id="current_patient_view" class="current_view_box">
                            <div id="form" v-if="current_patient != null">
                                <b-form @submit="onSubmitPatient($event)">
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
                                        v-model="patients[current_patient].email"
                                        type="email"
                                        disabled
                                    ></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-2" label="Password" label-for="create-2" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-2" type="text" v-model="patients[current_patient].password" disabled></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-4" label="First Name" label-for="create-4" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-4" type="text" v-model="patients[current_patient].first_name" disabled></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-5" label="Last Name" label-for="create-5" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-5" type="text" v-model="patients[current_patient].last_name" disabled></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-6" label="Middle Initial" label-for="create-6" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-6" type="text" v-model="patients[current_patient].middle_initial" disabled></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-7" label="Date of Birth" label-for="create-7" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-datepicker id="create-7" v-model="patients[current_patient].birth_date" class="mb-2" disabled></b-form-datepicker>
                                    </b-form-group>

                                    <b-form-group id="create-group-8" label="Phone Number" label-for="create-8" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-8" type="number" v-model="patients[current_patient].phone_number" disabled></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-9" label="Address" label-for="create-9" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-9" type="text" v-model="patients[current_patient].address" disabled></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-13" label="Sex" v-slot="{ ariaDescribedby }" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-radio v-model="patients[current_patient].sex" :aria-describedby="ariaDescribedby" name="some-radios" value="0" disabled>Male</b-form-radio>
                                    <b-form-radio v-model="patients[current_patient].sex" :aria-describedby="ariaDescribedby" name="some-radios" value="1" disabled>Female</b-form-radio>
                                    </b-form-group>

                                    <b-form-group id="create-group-14" label="Social Security Number:" label-for="create-14" label-cols-sm="4" content-cols-lg="7">
                                        <b-form-input id="create-14" type="number" v-model="patients[current_patient].SSN" disabled></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-15" label="Medical History:" label-for="create-15" label-cols-sm="4" content-cols-lg="7">
                                        <b-form-input id="create-15" type="text" v-model="patients[current_patient].Medical_history" disabled></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-16" label="Occupation Class:" label-for="create-16" label-cols-sm="4" content-cols-lg="7">
                                        <b-form-input id="create-16" type="text" v-model="patients[current_patient].Occupation_class" disabled></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-17" label="Race:" label-for="create-17" label-cols-sm="4" content-cols-lg="7">
                                        <b-form-input id="create-17" type="text" v-model="patients[current_patient].Race" disabled></b-form-input>
                                    </b-form-group>

                                    <b-button type="submit" id="createbtn" variant="success">Save Changes</b-button>
                                </b-form>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>

        <div v-if="current_view == VIEWS['vaccine']" id="vaccine_view" class="view">
            <transition name="fade">
                <create-vaccine v-if="create_vaccine == true" @close="createVaccineClosed()"/>
            </transition>

            <transition name="fade">
                <div id="vaccine_container" v-if="create_vaccine == false">
                    <h1 style="color: rgba(0,0,0,0.7);">Vaccines</h1>
                    <b-button v-if="create_vaccine == false" variant="outline-success" class="create_nurse" @click="create_vaccine = true;">Create Vaccine</b-button>
                    <div id="vaccine_card" class="view_card">
                        <b-list-group id="vaccine_list" class="view_list">
                            <b-list-group-item href="#" v-for="(vaccine, index) in vaccines" :key="vaccine.Name" @click="current_vaccine = index" :active="current_vaccine == index">{{vaccine.Name}}</b-list-group-item>
                        </b-list-group>

                        <div id="current_vaccine_view" class="current_view_box">
                            <div id="form" v-if="current_vaccine != null">
                                <b-form @submit="onSubmitVaccine($event)">
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
                                        v-model="vaccines[current_vaccine].Name"
                                        type="text"
                                        disabled
                                    ></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-2" label="Description" label-for="create-2" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-2" type="text" v-model="vaccines[current_vaccine].Description"></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-4" label="Company" label-for="create-4" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-4" type="text" v-model="vaccines[current_vaccine].Company"></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-5" label="Number of doses" label-for="create-5" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-5" type="number" v-model="vaccines[current_vaccine].Number_of_doses"></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-6" label="Available" label-for="create-6" label-cols-sm="4" content-cols-lg="7">
                                    <b-form-input id="create-6" type="number" v-model="vaccines[current_vaccine].Available"></b-form-input>
                                    </b-form-group>

                                    <b-form-group id="create-group-7" label="Hold" label-for="create-7" label-cols-sm="4" content-cols-lg="7">
                                        <b-form-input id="create-7" type="number" v-model="vaccines[current_vaccine].Hold"></b-form-input>
                                    </b-form-group>

                                    <b-button type="submit" id="createbtn" variant="success">Save Changes</b-button>
                                </b-form>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>

        <transition name="fade">
            <b-button v-if="current_view != VIEWS['menu']" @click="current_view = VIEWS['menu']" id="return_btn"><b-icon icon="arrow-return-left"></b-icon></b-button>
        </transition>
    </div>
</template>

<script>
import CreateNurse from './CreateNurse.vue'
import CreateVaccine from './CreateVaccine.vue'

export default {
  name: 'Admin',
  props: {
      user_info: Object,
      USER_TYPES: Object
  },
  components: {
    CreateNurse,
    CreateVaccine
  },
  data() {
    return {
      current_view: 0,
      VIEWS: {
          "menu": 0,
          "nurse": 1,
          "patient": 2,
          "vaccine": 3
      },
      create_nurse: false,
      create_vaccine: false,
      nurses: [],
      current_nurse: null,
      patients: [],
      current_patient: null,
      vaccines: [],
      current_vaccine: null,
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

  watch: {
    current_view: function (val) {
      if (val == 1) {
          this.getNurses();
      } else if (val == 2) {
          this.getPatients();
      } else if (val == 3) {
          this.getVaccines();
      } else if (val == 0) {
          this.create_nurse = false;
          this.create_vaccine = false;
          this.current_nurse = null;
          this.current_patient = null;
          this.current_vaccine = null;
      }
    }
  },

  created() {
    this.getNurses();
  },

  methods: {
    onSubmit2: async function(event) {
      if (event) {
        event.preventDefault()
      }
      console.log("HI2");
      var updated = await this.updateUserInfo();

      if (updated) {
        await this.getNurses();
        this.$bvToast.toast("Changes saved", {
            title: "Success",
            variant: "success",
            solid: true,
            toaster: 'b-toaster-bottom-right'
        })
      }
      
      return false;
    },

    onSubmitVaccine: async function(event) {
        if (event) {
            event.preventDefault()
        }
        console.log("HI2");
        var updated = await this.updateVaccineInfo();

        if (updated) {
            await this.getVaccines();
            this.$bvToast.toast("Changes saved", {
                title: "Success",
                variant: "success",
                solid: true,
                toaster: 'b-toaster-bottom-right'
            })
        }
        
        return false;
    },

    createNurseClosed: async function() {
        this.create_nurse = false;
        await this.getNurses();
    },

    createVaccineClosed: async function() {
        this.create_vaccine = false;
        await this.getVaccines();
    },

    deleteClick: async function(e) {
        if (e) {
            e.preventDefault();
        }


        let nurse_deleted = await this.deleteNurse();
        if (nurse_deleted) {
            let timeslots_updated = await this.preDeleteNurseTimeslots();
            if (timeslots_updated) {
                let timeslots_deleted = await this.deleteNurseTimeslots();
                if (timeslots_deleted) {
                    let user_deleted = await this.deleteUser();
                    if (user_deleted) {
                        this.current_nurse = null;
                        await this.getNurses();
                        this.$bvToast.toast("Nurse deleted", {
                            title: "Success",
                            variant: "success",
                            solid: true,
                            toaster: 'b-toaster-bottom-right'
                        });
                    }
                }
            }
        }
            
        
    },

    updateUserInfo() {
      let sql = `UPDATE Users SET email="${this.noApostraphe(this.nurses[this.current_nurse].email)}", password="${this.noApostraphe(this.nurses[this.current_nurse].password)}", first_name="${this.noApostraphe(this.nurses[this.current_nurse].first_name)}", last_name="${this.noApostraphe(this.nurses[this.current_nurse].last_name)}", middle_initial='${this.noApostraphe(this.nurses[this.current_nurse].middle_initial)}', birth_date='${this.noApostraphe(this.nurses[this.current_nurse].birth_date)}', phone_number="${this.noApostraphe(this.nurses[this.current_nurse].phone_number)}", address="${this.noApostraphe(this.nurses[this.current_nurse].address)}", sex=${this.nurses[this.current_nurse].sex} WHERE id=${this.nurses[this.current_nurse].user_id}`
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not update user info");
        } else {
            return true;
        }

      }).catch(err => { this.errorToast(err); return false; });
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

    updateVaccineInfo() {
        let sql = `UPDATE Vaccine SET Description="${this.noApostraphe(this.vaccines[this.current_vaccine].Description)}", Company="${this.noApostraphe(this.vaccines[this.current_vaccine].Company)}", Number_of_doses=${this.noApostraphe(this.vaccines[this.current_vaccine].Number_of_doses)}, Available=${this.noApostraphe(this.vaccines[this.current_vaccine].Available)}, Hold=${this.noApostraphe(this.vaccines[this.current_vaccine].Hold)} WHERE Name="${this.vaccines[this.current_vaccine].Name}"`
        console.log(sql);
        return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
            console.log(res);
            if (res.body.status == "ERROR") {
            throw Error("could not update vaccine");
            } else {
                return true;
            }

        }).catch(err => { this.errorToast(err); return false; });
    },

    deleteUser() {
      let sql = `DELETE FROM Users WHERE id=${this.nurses[this.current_nurse].user_id}`;
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not delete user");
        } else {
            return true;
        }

      }).catch(err => { this.errorToast(err); return false; });
    },

    deleteNurse() {
      let sql = `DELETE FROM Nurse WHERE id=${this.nurses[this.current_nurse][0]}`;
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not delete user");
        } else {
            return true;
        }

      }).catch(err => { this.errorToast(err); return false; });
    },

    preDeleteNurseTimeslots() {
      let sql = `SELECT timeslot_dt FROM Nurse_Scheduling_Mapping WHERE nurse_id=${this.nurses[this.current_nurse][0]} AND timeslot_dt > NOW()`;
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not retrieve nurse schedule");
        } else {
            if (res.body.rows.length == 0) {
                return true;
            } else {
                let timeslot_dts = res.body.rows.map(row => row.timeslot_dt);

                let sql = `UPDATE Timeslot SET num_nurses=(num_nurses-1) WHERE dt IN (${timeslot_dts})`;
                console.log(sql);
                return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
                    console.log(res);
                    if (res.body.status == "ERROR") {
                    throw Error("could not update past schedules");
                    } else {
                        return true;
                    }

                }).catch(err => { this.errorToast(err); return false; });
            }
        }

      }).catch(err => { this.errorToast(err); return false; });
    },

    deleteNurseTimeslots() {
      let sql = `DELETE FROM Nurse_Scheduling_Mapping WHERE nurse_id=${this.nurses[this.current_nurse][0]}`;
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not delete user");
        } else {
            return true;
        }

      }).catch(err => { this.errorToast(err); return false; });
    },

    getNurses() {
      let sql = `SELECT * FROM Nurse INNER JOIN Users ON Nurse.user_id=Users.id`;
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not retrieve nurses");
        } else {
          this.nurses = res.body.rows;
          console.log("NURSES");
          console.log(this.nurses);
          return true;
        }
      }).catch(err => { this.errorToast(err); return false; });
    },

    getPatients() {
      let sql = `SELECT * FROM Patient INNER JOIN Users ON Patient.user_id=Users.id`;
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not retrieve nurses");
        } else {
          this.patients = res.body.rows;
          console.log("PATIENTS");
          console.log(this.patients);
          return true;
        }
      }).catch(err => { this.errorToast(err); return false; });
    },

    getVaccines() {
      let sql = `SELECT * FROM Vaccine`;
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not retrieve nurses");
        } else {
          this.vaccines = res.body.rows;
          console.log("VACCINES");
          console.log(this.vaccines);
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

#admin {
    width: 100vw;
    max-height: 100vh;
    padding-top: 5vh;
}

h1 {
    padding-top: 1rem;
    font-family: roboto;
    font-size: 4rem;
}

#options {
    display: inline;
}

#options button {
    margin: 2rem;
    outline: none !important;
    border: none !important;
}

.view {
    position: relative;
    top: 0;
    width: 100vw;
    height: 100vh;
}

.create_nurse {
    position: fixed;
    bottom: 1%;
    right: 1%;
}

#nurses_card {
    display: flex;
    max-height: 80vh;
}

.view_card {
    display: flex;
    max-height: 80vh;
}

#nurses_list {
    width: 30%;
}

.view_list {
    width: 30%;
}

#current_nurse_view {
    width: 65%;
    min-height: 80vh;
    margin-left: 1rem;
    border-radius: 2rem;
    box-shadow: 0.5rem 0.5rem 1.5rem rgb(0 0 0 / 20%);
    padding-top: 3rem;
}

.current_view_box {
    width: 65%;
    min-height: 80vh;
    margin-left: 1rem;
    border-radius: 2rem;
    box-shadow: 0.5rem 0.5rem 1.5rem rgb(0 0 0 / 20%);
    padding-top: 3rem;
}

.list-group-item {
    border: none;
}

input {
  background: transparent;
  border: none;
  border-bottom: 1px rgba(0,0,0, 0.3) solid;
  border-radius: 0;
  transition: 0.5s;
}

input:focus {
  box-shadow: none;
  outline: 0;
  background: transparent;
  border-bottom-color: black;
}

#return_btn {
    /* background: rgba(0,0,0,0.3); */
    position: fixed;
    top: 5%;
    left: 1%;
    border-radius: 2rem;
    width: 4rem;
    border: none !important;
    box-shadow: 0.1rem 0.1rem 0.5rem rgb(0 0 0 / 30%);
}

</style>
