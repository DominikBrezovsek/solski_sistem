<template>
  <div class="subject-info" v-for="a in subject">
    <div class="tittle">
      <h1>{{ a.subject }}</h1>
    </div>
    <div class="description">
      <p>{{ a.description }}</p>
      <p @click="removeSubject" v-if="userType == 'student'"><img src="../assets/delete-icon.png" alt="un-enroll"></p>
    </div>

  </div>
</template>

<script lang="ts">
import axios from "axios";
import Subject from "@/views/StudentSubject.vue";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      subject: Array(),
      userType: ''
    }
  },
  methods: {
    getSubject() {
      let token = sessionStorage.getItem('token');
      const type = sessionStorage.getItem('type')
      if (type != null){
        this.userType = type
      }
      const subjectId = sessionStorage.getItem('subjectId')
      if (token != null && subjectId != null) {
        const path = 'https://smv.usdd.company/API/public/api/';
        const jwt = new FormData();
        jwt.append('token', token);
        jwt.append('subjectId', subjectId)
        axios.post(path + 'subjects/get', jwt)
            .then((response) => {
              if (response.data.subject != null) {
                for (let i = 0; i < (response.data.subject).length; i++) {
                  this.subject.push(response.data.subject[i]);
                }
              } else if (response.data.error == "token") {
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
    removeSubject() {
      let token = sessionStorage.getItem('token');
      const subjectId = sessionStorage.getItem('subjectId')
      if (token != null && subjectId != null) {
        const path = 'https://smv.usdd.company/API/public/api/';
        const jwt = new FormData();
        jwt.append('token', token);
        jwt.append('subjectId', subjectId)
        Swal.fire({
          title: 'Izpis iz predmeta?',
          text: 'Vaše oddaje bodo odstranjene, predmet pa odstranjen iz seznama. Nadaljujem?',
          icon: "question",
          confirmButtonText: 'Da, odstrani predmet s seznama.',
          confirmButtonColor: '#d1404d',
          showCancelButton: true,
          cancelButtonText: 'Ne, ne izpiši me iz predmeta.',
          cancelButtonColor: '#4377df'
        }).then((event) => {
          if (event.isConfirmed) {
            axios.post(path + 'sts/delete', jwt)
                .then((response) => {
                  if (response.data.success == "true") {
                    Swal.fire({
                      title: 'Predmet odstranjen',
                      text: 'Predmet uspešno odstranjen.',
                      icon: "success",
                      confirmButtonText: 'Razumem',
                      confirmButtonColor: '#4377df',
                    })
                        .then((event) => {
                          if (event.isConfirmed || event.isDismissed) {
                            this.$router.push('/classes')
                          }
                        })
                  }
                })
          } else {
            Swal.close()
          }
        })
      }
    },
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
  width: 82vw;
  height: 20vh;
  justify-content: flex-start;
  align-items: flex-start;
  padding-left: 2vw;
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
}
.description img {
  height: 3vh;
}
</style>