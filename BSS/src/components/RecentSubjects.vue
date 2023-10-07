<template>
  <div class="recent-subjects">
    <div class="recent-subject" v-for="subject in subjects">
      <h1>{{subject.description}}</h1>
    </div>
  </div>
</template>

<script lang="ts">

import axios from "axios";

export default {
  data() {
    return {
      subjects: Array ()
    }
  },
  methods: {
    getRecentSubjects(){
      let token = sessionStorage.getItem('token');
      if (token != null){
        const jwt = new FormData();
        jwt.append('token', token);
        axios.post('https://smv.usdd.company/API/public/api/dashboard/subjects', jwt)
            .then((response) => {
              if(response.data.studentId){
                this.subjects = response.data.subjects
              } else if (response.data.error == "session"){
                alert("Session has expired, please log in again!");
                sessionStorage.clear();
                localStorage.clear();
                this.$router.push('/');
              }
            })
      }
    }
  },
  created() {
    this.getRecentSubjects();
},
}
</script>

<style scoped>
.recent-subjects{
  height: 40vh;
  width: 80vw;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.recent-subject {
  height: 15vh;
  min-width: 15vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: #2e5baa;
  float: left;
}

.recent-subject h1 {
  color: white;
  font-size: medium;
  font-weight: bold;
}
</style>