<template>
  <div class="recent-subjects">
    <div class="recent-subject" v-for="subject in subjects">
      <h1>{{ subject.subject }}</h1>
    </div>
  </div>
</template>


<script lang="ts">

import axios from "axios";

export default {
  data() {
    return {
      subjects: Array()
    }
  },
  methods: {
    getRecentSubjects() {
      let token = sessionStorage.getItem('token');
      if (token != null) {
        const jwt = new FormData();
        jwt.append('token', token);
        axios.post('https://smv.usdd.company/API/public/api/dashboard/subjects', jwt)
            .then((response) => {
              if (response.data != null) {
                for (let i = 0; i < (response.data.subjects).length; i++) {
                  this.subjects.push(response.data.subjects[i]);
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
    this.getRecentSubjects();
  },
}
</script>


<style scoped>
.recent-subjects {
  height: 40vh;
  width: 80vw;
  display: flex;
  flex-direction: row;
  justify-content: space-evenly;
  align-items: center;
}

.recent-subject {
  height: 15vh;
  width: 15vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: #2e5baa;
}

.recent-subject h1 {
  color: white;
  font-size: medium;
  font-weight: bold;
}
</style>