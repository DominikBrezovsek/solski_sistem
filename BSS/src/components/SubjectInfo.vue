<template>
  <div class="subject-info" v-for="a in subject">
    <div class="tittle">
      <h1>{{ a.subject }}</h1>
    </div>
    <div class="tittle_subject">
      <h2>{{ a.tittle }}</h2>
    </div>
  </div>
</template>

<script lang="ts">
import axios from "axios";
import Subject from "@/views/Subject.vue";

export default {
  data() {
    return {
      subject: Array()
    }
  },
  methods: {
    getSubject() {
      let token = sessionStorage.getItem('token');
      const subjectId = sessionStorage.getItem('subjectId')
      if (token != null && subjectId != null) {
        const path = 'https://smv.usdd.company/API/public/api/';
        const jwt = new FormData();
        jwt.append('token', token);
        jwt.append('subjectId', subjectId)
        axios.post(path + 'subjects/get', jwt)
            .then((response) => {
              if (response.data != null) {
                for (let i = 0; i < (response.data.subject).length; i++) {
                  this.subject.push(response.data.subject[i]);
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
    this.getSubject()
  },
}
</script>

<style scoped>
.subject-info {
  display: flex;
  flex-direction: column;
  gap: 2vh;
  width: 85%;
  height: 45vh;
  justify-content: flex-start;
  align-items: flex-start;
  padding: 3vh;
}

.subject-info h2, p {
  padding: 2vh;
}

.subject-info h2 {
  width: 100%;
  padding: 2vh;
  border-bottom: 3px solid grey;
}

.tittle {
  width: 100%;
  margin-top: 1vh;
  margin-bottom: 1vh;
  margin-left: 1vh;
  display: flex;
  justify-content: center;
  color: grey;
  font-size: xx-large;
  flex-direction: column;
  overflow: hidden;
}

.tittle_subject {
  width: 100%;
  display: flex;
  justify-content: center;
  color: black;
  font-size: x-large;
  flex-direction: column;
  overflow: hidden;
}

</style>