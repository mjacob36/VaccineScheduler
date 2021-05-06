<template>
    <div id="nurse">
        <div id="select_container" v-if="create_record == false">
            <div id="calendar">
                <Calendar @dayclick='dayClicked' :attributes="calendar_attributes" is-expanded/>
            </div>
            <transition name="fade">
                <div v-if="selectedDay != null" id="current_day_view">
                    <p style="font-family: roboto">{{(selectedDay.getMonth()+1) + "/" + selectedDay.getDate() + "/" + selectedDay.getFullYear()}}</p>
                    <div id="times">
                        <b-button v-for="time in ALL_TIMES" :key="time.val" class="time_btn" @click="scheduleTimeslot(time.val)" :disabled="numNurses(time.val) >= 12 || isScheduled(time.val)">{{time.str}} - {{numNurses(time.val)}}/12 {{isScheduled(time.val, true)}}</b-button>
                    </div>
                </div>
            </transition>
        </div>
        <div id="current_schedule" v-if="create_record == false">
            <h1>My Schedule</h1>
            <b-card v-for="timeslot in myOrderedTimeslots" :key="timeslot.dt" :title="getDateString(timeslot.dt)" :sub-title="new Date(timeslot.dt).toLocaleTimeString()">
                <b-button v-if="new Date() <= new Date(timeslot.dt)" href="#" variant="danger" @click="cancelBtnPress(timeslot.dt)">Cancel</b-button>
                <b-button v-if="new Date() <= new Date(timeslot.dt)" id="create_record_btn" variant="outline-success" @click="vaccine_record_timeslot = timeslot; create_record = true;">Create Vaccine Record</b-button>
            </b-card>
        </div>

        <transition name="fade">
            <create-vaccine-record v-if="create_record == true && vaccine_record_timeslot != null" @close="createVaccineRecordClosed()" :user_info="user_info" :timeslot="vaccine_record_timeslot"/>
        </transition>
    </div>
</template>

<script>
import { Calendar } from 'v-calendar';
import CreateVaccineRecord from './CreateVaccineRecord.vue';

export default {
  name: 'Nurse',
  props: {
      user_info: Object,
      USER_TYPES: Object
  },
  components: {
    Calendar,
    CreateVaccineRecord
  },
  data() {
    return {
      ALL_TIMES: [{val: 8, str: "8am"}, {val: 9, str: "9am"}, {val: 10, str: "10am"}, {val: 11, str: "11am"}, {val: 12, str: "12pm"}, {val: 13, str: "1pm"}, {val: 14, str: "2am"}, {val: 15, str: "3am"}, {val: 16, str: "4am"}, {val: 17, str: "5am"}],
    
      selectedDay: null,
      timeslots: [],
      my_timeslots: [],
      create_record: false,
      vaccine_record_timeslot: null
    }
  },

  computed: {
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
            if (Object.keys(this.timeslot_map[key]).length > this.ALL_TIMES.length) {
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
    this.getTimeslots();
    this.getMyTimeslots();
  },

  methods: {
    errorToast(err) {
      this.$bvToast.toast(err + "", {
        title: "Error",
        variant: "danger",
        solid: true,
        toaster: 'b-toaster-bottom-right'
      })
    },

    createVaccineRecordClosed: async function() {
        this.create_record = false;
    },

    getDateString(d) {
        const days = {0: "Sunday", 1: "Monday", 2: "Tuesday", 3: "Wednesday", 4: "Thursday", 5: "Friday", 6: "Saturday"};
        const months = {0: "January", 1: "February", 2: "March", 3: "April", 4: "May", 5: "June", 6: "July", 7: "August", 8: "September", 9: "October", 10: "November", 11: "December"}
        d = new Date(d);
        return days[d.getDay()] + " " + months[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear();
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

    getMyTimeslots() {
      let sql = `SELECT * FROM Timeslot INNER JOIN Nurse_Scheduling_Mapping ON Timeslot.dt=Nurse_Scheduling_Mapping.timeslot_dt WHERE Nurse_Scheduling_Mapping.nurse_id=${this.user_info["nurse"].id}`;
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

        let timeslot_exists = await this.checkTimeslotExists();
        if (!timeslot_exists) {
            let timeslot_created = await this.createTimeslot();
            if (timeslot_created) {
                console.log("HI");
                let added_nurse = await this.addNurseToTimeslot();
                if (added_nurse) {
                    await this.incrementTimeslot("+1");
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
            let added_nurse = await this.addNurseToTimeslot();
            if (added_nurse) {
                await this.incrementTimeslot("+1");
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

    addNurseToTimeslot() {
        let sql = `INSERT INTO Nurse_Scheduling_Mapping (nurse_id, timeslot_dt) VALUES (${this.user_info.nurse.id}, '${this.selectedDt}')`;
        return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
            console.log(res);
            if (res.body.status == "ERROR") {
                throw Error("could not add nurse to timeslot");
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
        let sql = `UPDATE Timeslot SET num_nurses=(num_nurses${increment}) WHERE dt='${dt_val}'`
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

    cancelBtnPress: async function(dt) {
        let canceled = await this.cancelNurseTimeslot(dt);
        if (canceled) {
            await this.incrementTimeslot("-1", dt);
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

    cancelNurseTimeslot(dt) {
      let sql = `DELETE FROM Nurse_Scheduling_Mapping WHERE nurse_id=${this.user_info.nurse.id} AND timeslot_dt='${dt}'`;
      console.log(sql);
      return this.$http.post("http://linkupbeatz.com/API/COVID", {auth: "zBhk9XhwRNdrEBZYth4h46cPH4gXqTcpv9YvFJNfsEvDSPbSdh", sql: sql}).then(res => {
        console.log(res);
        if (res.body.status == "ERROR") {
          throw Error("could not delete nurse's timeslot");
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

#nurse {
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
}

.btn-danger {
    font-size: 0.7rem;
}

#create_record_btn {
    font-size: 0.7rem;
    margin-left: 1rem;
}

</style>
