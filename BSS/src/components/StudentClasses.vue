<template>
  <div class="Student_classes">
    <div class="tittle">
      <h1>Predmeti</h1>
    </div>
    <div class="classes">
      <div class="Student_class" v-for="a in subjects" @click="openSubject(a.id)">
        <h1>{{ a.subject }}</h1>
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
    getStudentClasses() {
      let token = sessionStorage.getItem('token');
      if (token != null) {
        const path = 'https://smv.usdd.company/API/public/api/';
        const jwt = new FormData();
        jwt.append('token', token);
        axios.post(path + 'sts/get', jwt)
            .then((response) => {
              if (response.data.error) {
                sessionStorage.setItem('expired', response.data.error)
              }
              if (response.data.subjects) {
                for (let i = 0; i < (response.data.subjects).length; i++) {
                  this.subjects.push(response.data.subjects[i]);
                }
              } else if (sessionStorage.getItem('expired') == "token") {
                Swal.fire({
                  title: 'Seja je potekla',
                  text: 'Za nadaljevanje se ponovno prijavite.',
                  icon: "warning",
                  confirmButtonText: 'Prijava',
                  confirmButtonColor: '#4377df'
                }).then((event) => {
                  if (event.isConfirmed) {
                    sessionStorage.clear();
                    localStorage.clear();
                    this.$router.push('/');
                  } else {
                    sessionStorage.clear();
                    localStorage.clear();
                    this.$router.push('/');
                  }
                })
              }
            })
      }
    },
    openSubject(id: string) {
      sessionStorage.setItem('subjectId', id);
      this.$router.push('/subject-s');
    }
  },
  created() {
    this.getStudentClasses();
  },
}
</script>

<style scoped>
.Student_classes {
  height: 40vh;
  width: 82vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  padding-left: 2vw;
}

.Student_class {
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

.Student_class h1 {
  color: white;
  font-size: x-large;
  font-weight: bold;
}

.Student_class:hover {
  border-bottom: 4px solid #5891d3;
  transition: 0.2s ease-in-out;
  cursor: pointer;
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
}

.classes {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  flex-wrap: wrap;
  gap: 8vw;
  width: 100%;
  height: 100%;
  align-items: center;
  padding-left: 5vw;
  padding-bottom: 3vh;
  padding-top: 3vh;
  overflow-y: scroll;
  scroll-behavior: smooth;
}
</style>