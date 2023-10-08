<template>
  <div class="recent-assignments">
    <div class="tittle">
      <h1>Zadnje naloge</h1>
    </div>
    <div class="assignment">
      <div class="recent-assignment" v-for ="a in assignment">
        <h2>{{a.description}}</h2>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import axios from "axios";

export default {
  data() {
    return {
      assignment: Array()
    }
  },
  methods: {
    getRecentAssignmnent() {
      let token = sessionStorage.getItem('token');
      if (token != null) {
        const jwt = new FormData();
        jwt.append('token', token);
        axios.post('https://smv.usdd.company/API/public/api/dashboard/assignments', jwt)
            .then((response) => {
              if (response.data != null) {
                for (let i = 0; i < (response.data.assignments).length; i++) {
                  this.assignment.push(response.data.assignments[i]);
                }
              } else if (response.data.error == "session") {
                alert("Session has expired, please log in again!");
                sessionStorage.clear();
                localStorage.clear();
                this.$router.push('/');
              }
            })
      } else {
        this.$router.push('/');
      }
    }
  },
  created() {
    this.getRecentAssignmnent();
  },
}
</script>

<style scoped>
.recent-assignments{
  height: 60vh;
  width: 85vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  counter-reset: section;
}
.recent-assignment{
  height: 2vh;
  width: 20vw;
  display: flex;
  flex-direction: column;
  justify-content: center;
  margin-bottom: 2vh;
  margin-left: 2vw;
}
.recent-assignment h2 {
  color: black;
  font-size: large;
}
.recent-assignment h2::before {
  counter-increment: section;
  content: counter(section) ": ";
}

.tittle{
  width: 100%;
  margin-top: 2vh;
  margin-bottom: 2vh;
  margin-left: 5vh;
  display: flex;
  justify-content: center;
  color: grey;
  font-size: xx-large;
  flex-direction: column;
  overflow: hidden;
}
.assignment{
  display: flex;
  flex-direction: column;
  justify-content: space-evenly;
  width: 100%;
  margin-bottom: 8vh;
}
</style>