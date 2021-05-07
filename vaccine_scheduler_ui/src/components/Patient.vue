<template>
    <div id="patient">
        <div id="select_container">
            <div id="vaccines">
                <b-list-group horizontal>
                    <b-list-group-item href="#" v-for="(vaccine, index) in vaccines" :key="vaccine.Name" @click="selected_vaccine_index = index;" :active="selected_vaccine_index == index" :disabled="vaccine.Available <= 0">
                        <b-card style="border-radius: 3rem" :title="vaccine.Name" :sub-title="vaccine.Company">
                            <b-card-text style="font-size: 0.7rem">{{vaccine.Description}}</b-card-text>
                            <b-card-text style="font-size: 0.7rem">Number of doses: {{vaccine.Number_of_doses}}</b-card-text>

                            <b-badge variant="primary" pill>Available: {{vaccine.Available}}</b-badge>
                            <b-badge style="margin-left: 1rem" variant="danger" pill>Hold: {{vaccine.Hold}}</b-badge>
                        </b-card>
                    </b-list-group-item>
                </b-list-group>
            </div>
            <div id="calendar">
                <Calendar @dayclick='dayClicked' :attributes="calendar_attributes" is-expanded/>
            </div>
            <transition name="fade">
                <div v-if="selectedDay != null" id="current_day_view">
                    <p style="font-family: roboto">{{(selectedDay.getMonth()+1) + "/" + selectedDay.getDate() + "/" + selectedDay.getFullYear()}}</p>
                    <div id="times">
                        <b-button v-for="time in ALL_TIMES" :key="time.val" class="time_btn" @click="scheduleTimeslot(time.val)" :disabled="numPatients(time.val) >= 100 || numPatients(time.val) >= 10 * numNurses(time.val) || isScheduled(time.val)">
                            {{time.str}} - {{isScheduled(time.val, true)}} 
                            <b-badge variant="primary" pill>Patients: {{numPatients(time.val)}}/{{Math.min(100, numNurses(time.val)*10)}}</b-badge>    
                            <b-badge style="margin-left: 0.5rem" variant="primary" pill>Nurses: {{numNurses(time.val)}}/12</b-badge>
                        </b-button>
                    </div>
                </div>
            </transition>
        </div>
        <div id="current_schedule">
            <h1>My Schedule</h1>
            <b-card v-for="timeslot in myOrderedTimeslots" :key="timeslot.dt" :title="getDateString(timeslot.dt)" :sub-title="new Date(timeslot.dt).toLocaleTimeString()">
                <b-card-text style="font-size: 0.7rem">Vaccine: {{timeslot.vaccine}}</b-card-text>
                <b-button v-if="new Date() <= new Date(timeslot.dt)" href="#" variant="danger" @click="cancelBtnPress(timeslot.dt, timeslot.vaccine)">Cancel</b-button>
            </b-card>
            <h1>My Vaccine Records</h1>
            <b-card v-for="record in vaccine_records" :key="record.timeslot_dt" :title="getDateString(record.timeslot_dt)" :sub-title="new Date(record.timeslot_dt).toLocaleTimeString()">
                <b-card-text style="font-size: 0.7rem">Vaccine: {{record.vaccine}}</b-card-text>
                <b-card-text style="font-size: 0.7rem">Dose Number: {{record.dose_number}}</b-card-text>
                <b-card-text style="font-size: 0.7rem">Nurse: {{record.nurse_fname + " " + record.nurse_lname}}</b-card-text>
            </b-card>
        </div>
    </div>
</template>

<script>
import { Calendar } from 'v-calendar';

export default {
  name: 'Patient',
  props: {
      user_info: Object,
      USER_TYPES: Object
  },
  components: {
    Calendar
  },
  data() {
    return {
      ALL_TIMES: [{val: 8, str: "8am"}, {val: 9, str: "9am"}, {val: 10, str: "10am"}, {val: 11, str: "11am"}, {val: 12, str: "12pm"}, {val: 13, str: "1pm"}, {val: 14, str: "2pm"}, {val: 15, str: "3pm"}, {val: 16, str: "4pm"}, {val: 17, str: "5pm"}],
    
      selectedDay: null,
      timeslots: [],
      my_timeslots: [],
      vaccines: [],
      create_record: false,
      vaccine_record_timeslot: null,
      selected_vaccine_index: null,
      vaccine_records: []
    }
  },

  computed: {
    selected_vaccine() {
        if (this.selected_vaccine_index == null) {
            return null;
        }
        return this.vaccines[this.selected_vaccine_index];
    },
    
    timeslot_map() {
        var the_map = {};
        for (let timeslot of this.timeslots) {
            let dt = new Date(timeslot.dt);
            if (!((dt.getFullYear() + "-" + (dt.getMonth()+1) + "-" + dt.getDate()) in the_map)) {
                the_map[dt.getFullYear() + "-" + (dt.getMonth()+1) + "-" + dt.getDate()] = {};
            }
            the_map[dt.getFullYear() + "-" + (dt.getMonth()+1) + "-" + dt.getDate()][dt.getHours()] = timeslot;
        }
        return the_map
    },

    mytimeslots_map() {
        var the_map = {};
        for (let timeslot of this.my_timeslots) {
            let dt = new Date(timeslot.dt);
            if (!((dt.getFullYear() + "-" + (dt.getMonth()+1) + "-" + dt.getDate()) in the_map)) {
                the_map[dt.getFullYear() + "-" + (dt.getMonth()+1) + "-" + dt.getDate()] = {};
            }
            the_map[dt.getFullYear() + "-" + (dt.getMonth()+1) + "-" + dt.getDate()][dt.getHours()] = timeslot;
        }
        return the_map
    },

    selectedDt() {
        return this.selectedDay.getFullYear() + "-" + (this.selectedDay.getMonth()+1) + "-" + this.selectedDay.getDate() + " " + this.selectedDay.getHours() + ":00:00";
    },

    calendar_attributes() {
        var attributes = [
            {
            key: 'today',
            highlight: true,
            dates: new Date(),
            },
        ];

        for (let timeslot of this.my_timeslots) {
            attributes.push({key: timeslot.dt, bar: "red", dates: new Date(timeslot.dt)});
        }

        for (let key in this.timeslot_map) {
            let filled_hours = Object.keys(this.timeslot_map[key]).filter(x => this.timeslotHasMaxPatients(this.timeslot_map[key][x]));
            if (filled_hours.length >= this.ALL_TIMES.length) {
                attributes.push({key: key, bar: "gray", dates: new Date(key)})
            }
        }

        return attributes;
    },

    myOrderedTimeslots() {
        let myslots = this.my_timeslots;
        myslots.sort((a, b) => (new Date(a.dt) > new Date(b.dt)) ? -1 : 1);
        return myslots;
    }
  },

  watch: {

  },

  created() {
    this.getVaccines();
    this.getMyVaccineRecords();
    this.getTimeslots();
    this.getMyTimeslots();
  },

  methods: {
    timeslotHasMaxPatients(t) {
        return t.num_patients >= 100 || t.num_patients >= 10 * t.num_nurses;
    },

    isVaccineUsedBefore(vaccine) {
        if (this.vaccine_records.length == 0) {
            return false;
        }

        let tmp_records = this.vaccine_records;
        let tmp_vaccines = [...this.vaccines];
        tmp_records.sort((a, b) => (new Date(a.timeslot_dt) > new Date(b.timeslot_dt)) ? -1 : 1);
        let last_vaccine = tmp_vaccines.filter(x => x.Name == tmp_records[0].vaccine)[0];
        console.log("LAST VACCINE: ");
        console.log(last_vaccine);

        if (tmp_records[0].dose_number == last_vaccine.Number_of_doses) {
            return false;
        } else {
            return tmp_records[0].vaccine != vaccine;
        }
    },
    

    errorToast(err) {
      this.$bvToast.toast(err + "", {
        title: "Error",
        variant: "danger",
        solid: true,
        toaster: 'b-toaster-bottom-right'
      })
    },

    getDateString(d) {
        const days = {0: "Sunday", 1: "Monday", 2: "Tuesday", 3: "Wednesday", 4: "Thursday", 5: "Friday", 6: "Saturday"};
        const months = {0: "January", 1: "February", 2: "March", 3: "April", 4: "May", 5: "June", 6: "July", 7: "August", 8: "September", 9: "October", 10: "November", 11: "December"}
        d = new Date(d);
        return days[d.getDay()] + " " + months[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear();
    },

    numPatients(hour) {
        if (!this.timeslot_map[this.selectedDay.getFullYear() + "-" + (this.selectedDay.getMonth()+1) + "-" + this.selectedDay.getDate()] || !this.timeslot_map[this.selectedDay.getFullYear() + "-" + (this.selectedDay.getMonth()+1) + "-" + this.selectedDay.getDate()][hour]) {
            return 0;
        }

        return this.timeslot_map[this.selectedDay.getFullYear() + "-" + (this.selectedDay.getMonth()+1) + "-" + this.selectedDay.getDate()][hour].num_patients;
    },

    numNurses(hour) {
        if (!this.timeslot_map[this.selectedDay.getFullYear() + "-" + (this.selectedDay.getMonth()+1) + "-" + this.selectedDay.getDate()] || !this.timeslot_map[this.selectedDay.getFullYear() + "-" + (this.selectedDay.getMonth()+1) + "-" + this.selectedDay.getDate()][hour]) {
            return 0;
        }

        return this.timeslot_map[this.selectedDay.getFullYear() + "-" + (this.selectedDay.getMonth()+1) + "-" + this.selectedDay.getDate()][hour].num_nurses;
    },

    isScheduled(hour, need_string=false) {
        if (!this.mytimeslots_map[this.selectedDay.getFullYear() + "-" + (this.selectedDay.getMonth()+1) + "-" + this.selectedDay.getDate()] || !this.mytimeslots_map[this.selectedDay.getFullYear() + "-" + (this.selectedDay.getMonth()+1) + "-" + this.selectedDay.getDate()][hour]) {
            if (need_string) {
                return "";
            }
            return false;
        } else {
            if (need_string) {
                return " [SCHEDULED]";
            }
            return true;
        }
    },

    getTimeslots() {
      let sql = `SELECT * FROM Timeslot WHERE dt >= NOW()`;
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not retrieve timeslots");
        } else {
          this.timeslots = res.body.rows;
          console.log("TIMESLOTS");
          console.log(this.timeslots);
          return true;
        }
      }).catch(err => { this.errorToast(err); return false; });
    },

    getVaccines() {
      let sql = `SELECT * FROM Vaccine`;
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not retrieve vaccines");
        } else {
          this.vaccines = res.body.rows;
          console.log("vaccines");
          console.log(this.vaccines);
          return true;
        }
      }).catch(err => { this.errorToast(err); return false; });
    },

    getMyTimeslots() {
      let sql = `SELECT * FROM Timeslot INNER JOIN Patient_Scheduling_Mapping ON Timeslot.dt=Patient_Scheduling_Mapping.timeslot_dt WHERE Patient_Scheduling_Mapping.patient_id=${this.user_info.patient.id}`;
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not retrieve user's timeslots");
        } else {
          this.my_timeslots = res.body.rows;
          console.log("MY TIMESLOTS");
          console.log(this.my_timeslots);
          return true;
        }
      }).catch(err => { this.errorToast(err); return false; });
    },

    getMyVaccineRecords() {
      let sql = `SELECT Vaccine_Record.*, Users.first_name AS nurse_fname, Users.last_name AS nurse_lname FROM Vaccine_Record INNER JOIN Nurse ON nurse_id=Nurse.id INNER JOIN Users ON Users.id=Nurse.user_id WHERE patient_id=${this.user_info.patient.id}`;
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not retrieve user's timeslots");
        } else {
          this.vaccine_records = res.body.rows;
          console.log("RECORDS");
          console.log(this.vaccine_records);
          return true;
        }
      }).catch(err => { this.errorToast(err); return false; });
    },

    dayClicked(day) {
        this.selectedDay = day.date;
        console.log(day.date);
    },

    scheduleTimeslot: async function(hour) {
        console.log("USER");
        console.log(this.user_info);
        console.log("HOUR: " + hour);
        this.selectedDay = new Date(this.selectedDay.setHours(hour));
        if (this.selectedDay < new Date()) {
            this.errorToast("Cannot schedule time that has passed");
            return;
        }

        if (!this.selected_vaccine) {
            this.errorToast("Please select a vaccine to schedule a timeslot");
            return;
        }

        let timeslot_exists = await this.checkTimeslotExists();
        if (!timeslot_exists) {
            let timeslot_created = await this.createTimeslot();
            if (timeslot_created) {
                console.log("HI");
                let added_patient = await this.addPatientToTimeslot();
                if (added_patient) {
                    await this.incrementVaccineAvailability("-1", "+1");
                    await this.incrementTimeslot("+1");
                    await this.getVaccines();
                    await this.getMyVaccineRecords();
                    await this.getTimeslots();
                    await this.getMyTimeslots(); 
                    this.$bvToast.toast("Timeslot scheduled", {
                        title: "Success",
                        variant: "success",
                        solid: true,
                        toaster: 'b-toaster-bottom-right'
                    });
                }
            }
        } else {
            let added_patient = await this.addPatientToTimeslot();
            if (added_patient) {
                await this.incrementVaccineAvailability("-1", "+1");
                await this.incrementTimeslot("+1");
                await this.getVaccines();
                await this.getMyVaccineRecords();
                await this.getTimeslots();
                await this.getMyTimeslots(); 
                this.$bvToast.toast("Timeslot scheduled", {
                    title: "Success",
                    variant: "success",
                    solid: true,
                    toaster: 'b-toaster-bottom-right'
                });
            }
        }
    },

    checkTimeslotExists() {
        let sql = `SELECT COUNT(dt) FROM Timeslot WHERE dt='${this.selectedDt}'`;
        return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
            console.log(res);
            if (res.body.status == "ERROR") {
                throw Error("could not retrieve user's timeslots");
            } else {
                console.log(res.body);
                // this.my_timeslots = res.body.rows;
                // console.log("MY TIMESLOTS");
                // console.log(this.my_timeslots);
                return parseInt(res.body.rows[0]['COUNT(dt)']) != 0;
            }
        }).catch(err => { this.errorToast(err); return false; });
    },

    createTimeslot() {
        let sql = `INSERT INTO Timeslot (dt, num_nurses, num_patients) VALUES ('${this.selectedDt}', 0, 0)`;
        return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
            console.log(res);
            if (res.body.status == "ERROR") {
                throw Error("could not create timeslots");
            } else {
                console.log(res.body);
                // this.my_timeslots = res.body.rows;
                // console.log("MY TIMESLOTS");
                // console.log(this.my_timeslots);
                return true;
            }
        }).catch(err => { this.errorToast(err); return false; });
    },

    addPatientToTimeslot() {
        let sql = `INSERT INTO Patient_Scheduling_Mapping (patient_id, timeslot_dt, vaccine) VALUES (${this.user_info.patient.id}, '${this.selectedDt}', '${this.selected_vaccine.Name}')`;
        return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
            console.log(res);
            if (res.body.status == "ERROR") {
                throw Error("could not add patient to timeslot");
            } else {
                console.log(res.body);
                return true;
            }
        }).catch(err => { this.errorToast(err); return false; });
    },

    incrementTimeslot(increment, dt) {
        let dt_val = dt;
        if (dt_val == null) {
            dt_val = this.selectedDt;
        }
        let sql = `UPDATE Timeslot SET num_patients=(num_patients${increment}) WHERE dt='${dt_val}'`
        return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
            console.log(res);
            if (res.body.status == "ERROR") {
                throw Error("could not update timeslot");
            } else {
                console.log(res.body);
                return true;
            }
        }).catch(err => { this.errorToast(err); return false; });
    },

    incrementVaccineAvailability(increment_availability, increment_hold, vaccine) {
        let vaccine_name = vaccine;
        if (vaccine_name == null) {
            vaccine_name = this.selected_vaccine.Name;
        }
        let sql = `UPDATE Vaccine SET Available=(Available${increment_availability}), Hold=(Hold${increment_hold}) WHERE Name='${vaccine_name}'`;
        console.log(sql);
        return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
            console.log(res);
            if (res.body.status == "ERROR") {
                throw Error("could not update vaccine");
            } else {
                console.log(res.body);
                return true;
            }
        }).catch(err => { this.errorToast(err); return false; });
    },

    cancelBtnPress: async function(dt, vaccine) {
        let canceled = await this.cancelPatientTimeslot(dt);
        if (canceled) {
            await this.incrementVaccineAvailability("+1", "-1", vaccine);
            await this.incrementTimeslot("-1", dt);
            await this.getVaccines();
            await this.getMyVaccineRecords();
            await this.getTimeslots();
            await this.getMyTimeslots(); 
            this.$bvToast.toast("Timeslot canceled", {
                title: "Success",
                variant: "success",
                solid: true,
                toaster: 'b-toaster-bottom-right'
            });
        }
    },

    cancelPatientTimeslot(dt) {
      let sql = `DELETE FROM Patient_Scheduling_Mapping WHERE patient_id=${this.user_info.patient.id} AND timeslot_dt='${dt}'`;
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not delete patient's timeslot");
        } else {
            return true;
        }

      }).catch(err => { this.errorToast(err); return false; });
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

#patient {
    width: 100vw;
    max-height: 100vh;
    padding-top: 5vh;
    display: flex;
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

#calendar {
    width: 54%;
    /* height: 100%; */
    margin: auto;
    box-shadow: 0.5rem 0.5rem 1rem rgb(0 0 0 / 10%);
    border: none;
    outline: none;
}

#current_day_view {
    margin: auto;
    margin-top: 3rem;
    width: 40%;
    padding: 2rem;
    box-shadow: 0.5rem 0.5rem 1rem rgb(0 0 0 / 10%);
    border-radius: 1rem;
}

.time_btn {
    background: transparent;
    margin: 0.5rem;
    color: black;
    font-size: 0.7rem;
    border: none;
    transition: 0.5s;
    width: 100%;
    box-shadow: 0.1rem 0.3rem 0.5rem rgb(0 0 0 / 5%);
}

#select_container {
    width: 50%;
    padding-top: 5rem;
}

.vc-container {
    border: none;
}

#current_schedule {
    width: 80%;
    max-height: 100vh;
    margin: auto;
    overflow-y: scroll;
    font-family: 'roboto';
    padding: 2rem;
}

.card {
    border: none;
    box-shadow: 0.1rem 0.1rem 0.5rem rgb(0 0 0 / 10%);
    margin-bottom: 1rem;
    transition: 0.5s;
}

.btn-danger {
    font-size: 0.7rem;
}

#create_record_btn {
    font-size: 0.7rem;
    margin-left: 1rem;
}

.list-group-item.active {
    z-index: 2;
    color: inherit;
    background-color: unset;
    border-color: unset;
}

.list-group-item.active .card {
    box-shadow: 0.1rem 0.1rem 1rem rgb(0 26 88 / 43%);
}

</style>
