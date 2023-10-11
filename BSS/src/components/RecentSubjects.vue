<template>
  <div class="recent-subjects">
    <div class="tittle">
      <h1>Zadnji predmeti</h1>
    </div>
    <div class="subjects">
      <div class="recent-subject" v-for="subject in subjects" @click="openSubject(subject.id)">
        <h1>{{ subject.subject }}</h1>
      </div>
    </div>
  </div>
</template>


<script lang="ts">

import axios from "axios";
import Swal from "sweetalert2";

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
              } else if (response.data.error == "token") {
                Swal.fire({
                  title: 'Seja je potekla',
                  text: 'Za nadaljevanje se ponovno prijavite.',
                  icon: "warning",
                  confirmButtonText: 'Prijava',
                  confirmButtonColor: '4377df'
                }) .then((event) => {
                  if (event.isConfirmed){
                    sessionStorage.clear();
                    localStorage.clear();
                    this.$router.push('/');
                  }
                })
              }
            })
      }
    },
      openSubject (id: string){
      sessionStorage.setItem('subjectId', id);
      this.$router.push('/subject');
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
  width: 85vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
}

.recent-subject {
  height: 20vh;
  width: 15vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: #2e5baa;
  border-radius: 2vh;
  box-shadow: 4px 3px 14px 0px #00000085
}
.recent-subject:hover {
  border-bottom: 4px solid #5891d3;
  transition: 0.2s ease-in-out;
  cursor: pointer;
}

.recent-subject h1 {
  color: white;
  font-size: x-large;
  font-weight: bold;
}

.tittle{
  width: 100%;
  margin-top: 2vh;
  margin-bottom: 1vh;
  margin-left: 5vh;
  display: flex;
  justify-content: center;
  color: grey;
  font-size: xx-large;
  flex-direction: column;
  overflow: hidden;
}

.subjects{
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  gap: 8vw;
  width: 100%;
  height: 100%;
  align-items: center;
  padding-left: 5vw;
}
</style>