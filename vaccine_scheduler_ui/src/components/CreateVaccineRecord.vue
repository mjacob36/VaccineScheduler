<template>
    <div id="create_vaccine_record_form">
        <b-button id="close" size="lg" @click="close()"><b-icon icon="x-circle"></b-icon></b-button>
        <div id="patient_list">
            <h1>Select Patient to create Vaccine Record</h1>
            <b-list-group>
                <!-- <b-list-group-item>
                    <b-list-group horizontal>
                        <b-list-group-item>Patient</b-list-group-item>
                        <b-list-group-item>Dapibus ac facilisis in</b-list-group-item>
                        <b-list-group-item>Morbi leo risus</b-list-group-item>
                    </b-list-group>
                </b-list-group-item> -->
                <b-list-group-item href="#" v-for="timeslot_patient in timeslot_patients" :key="timeslot_patient.patient_id" @click="createVaccineRecordClick(timeslot_patient)">
                    {{timeslot_patient.first_name + " " + timeslot_patient.middle_initial + " " + timeslot_patient.last_name}}<b-badge style="margin-left: 1rem" variant="primary" pill>{{timeslot_patient.vaccine + ": " + nextDoseNumber(timeslot_patient)}}</b-badge>
                </b-list-group-item>
            </b-list-group>
        </div>
    </div>
</template>

<script>
export default {
  name: 'CreateVaccine',
  props: {
      user_info: Object,
      timeslot: Object
  },
  data() {
    return {
      user_form: {
        patient_email: null,
        date: null,
        time: null,
        vaccine: null,
        dose_number: null,
      },

      timeslot_patients: []
    }
  },

  created() {
      this.getTimeslotPatients();
  },

  methods: {
    nextDoseNumber(p) {
        if (!p.dose_number) {
            return 1;
        } else {
            return p.dose_number + 1;
        }
    },
    
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

    getTimeslotPatients() {
        let sql = `SELECT Patient_Scheduling_Mapping.patient_id, Patient_Scheduling_Mapping.vaccine, Users.first_name, Users.last_name, Users.middle_initial, Vaccine_Record.dose_number FROM Timeslot INNER JOIN Patient_Scheduling_Mapping ON Timeslot.dt=Patient_Scheduling_Mapping.timeslot_dt INNER JOIN Patient ON Patient.id=Patient_Scheduling_Mapping.patient_id INNER JOIN Users ON Patient.user_id=Users.id LEFT JOIN Vaccine_Record ON Patient_Scheduling_Mapping.patient_id=Vaccine_Record.patient_id WHERE Timeslot.dt='${this.timeslot.dt}'`;
        console.log(sql);
        return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
            console.log(res);
            if (res.body.status == "ERROR") {
                throw Error("Could not get timeslot patients");
            } else {
                console.log(res.body);
                // this.timeslot_patients = res.body.rows;
                var timeslot_patients = res.body.rows;
                if (timeslot_patients.length == 0) {
                    this.timeslot_patients = timeslot_patients;
                    return true;
                } else {
                    var patient_ids = timeslot_patients.map(x => x.patient_id)
                    let sql = `SELECT patient_id FROM Vaccine_Record WHERE patient_id IN (${patient_ids.join()}) AND timeslot_dt='${this.timeslot.dt}'`
                    return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
                        console.log(res);
                        if (res.body.status == "ERROR") {
                            throw Error("Could not get timeslot patients");
                        } else {
                            console.log("HERE");
                            console.log(res.body);
                            let bad_ids = res.body.rows;
                            bad_ids = bad_ids.map(x => x.patient_id);
                            this.timeslot_patients = timeslot_patients.filter(x => !bad_ids.includes(x.patient_id));
                            console.log("PATIENTS");
                            console.log(timeslot_patients);
                            return true;
                        }
                    }).catch(err => { this.errorToast(err); return false; });
                }
            }
        }).catch(err => { this.errorToast(err); return false; });
    },

    createVaccineRecordClick: async function(timeslot_patient) {
        let created = await this.createVaccineRecord(timeslot_patient);
        if (created) {
            await this.getTimeslotPatients();
            this.$bvToast.toast(`Created Vaccine Record for ${timeslot_patient.first_name + " " + timeslot_patient.last_name} | ${timeslot_patient.vaccine + " Dose #" + this.nextDoseNumber(timeslot_patient)}`, {
                title: "Success",
                variant: "success",
                solid: true,
                toaster: 'b-toaster-bottom-right'
            });
        }
    },

    createVaccineRecord(timeslot_patient) {
      let sql = `INSERT INTO Vaccine_Record (timeslot_dt, patient_id, nurse_id, dose_number, vaccine) VALUES ('${this.timeslot.dt}', ${timeslot_patient.patient_id}, ${this.user_info.nurse.id}, ${this.nextDoseNumber(timeslot_patient)}, '${timeslot_patient.vaccine}')`;
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("Could not create vaccine record");
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

#create_vaccine_record_form {
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

#patient_list {
    width: 50%;
    margin: auto;
    margin-top: 8rem;
    /* min-height: 80vh; */
    box-shadow: 0.1rem 0.3rem 0.5rem rgb(0 0 0 / 5%);
    border-radius: 2rem;
    padding: 3rem;
    height: 80vh;
    font-family: roboto;
    overflow-y: scroll;
}
</style>