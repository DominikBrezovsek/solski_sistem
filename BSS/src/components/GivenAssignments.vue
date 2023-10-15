<template>
  <div class="given-assignments">
    <div class="tittle">
      <h1>Dodeljene naloge</h1>
    </div>
    <div class="assignment">
      <div class="given-assignment" v-for="a in assignment">
        <div @click="assignmentInfo(a.id)" class="assignment-inner-div">
          <h2>{{ a.tittle }}</h2>
          <p>{{ a.subject }}</p>
          <p>{{ a.deadline }}</p>
        </div>
        <div class="button">
          <p><img src="../assets/delete-icon.png" alt="Izbriši" @click="deleteAssignment(a.file)"></p>
        </div>
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
      assignment: Array(),
      type: ""
    }
  },
  methods: {
    getGivAssignment() {
      let token = sessionStorage.getItem('token');
      const subject = sessionStorage.getItem('subjectId');
      const userType = sessionStorage.getItem('type')
      if (token != null && subject != null && userType != null) {
        const jwt = new FormData();
        jwt.append('token', token);
        jwt.append('subjectId', subject)
        jwt.append('userType', userType)
        axios.post('https://smv.usdd.company/API/public/api/assignment/get-all', jwt)
            .then((response) => {
              if (response.data.assignments != null) {
                for (let i = 0; i < (response.data.assignments).length; i++) {
                  this.assignment.push(response.data.assignments[i]);
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
    assignmentInfo(id: string) {
      sessionStorage.setItem('assignmentId', id);
      this.$router.push('/assignments');
    },
    deleteAssignment(file: string) {
      const token = sessionStorage.getItem('token');
      const subjectId = sessionStorage.getItem('subjectId');
      if (token != null && subjectId != null) {
        /*define the API URL*/
        const path = "https://smv.usdd.company/API/public/api/"
        /*Create new FormData instance and append the needed data*/
        const data = new FormData();
        data.append('token', token);
        data.append('subjectId', subjectId);

        /*show an alert-box that prevents user from accidentally deleting a submission*/

        Swal.fire({
          title: "Odstrani nalogo?",
          text: "Naloga bo odstranjena iz sistema, datoteka pa izbrisana. Tega dejanja ni mogoče razveljaviti. Želite nadaljevati?",
          icon: "question",
          confirmButtonText: 'Da, odstrani nalogo',
          confirmButtonColor: "#e63946",
          showDenyButton: true,
          denyButtonText: 'Ne, zadovoljen sem s trenutno nalogo.',
          denyButtonColor: '#4377df'
        })
            .then(function (isSuccess) {
              if (isSuccess.isConfirmed) {
                axios.post(path + 'assignment/deleteAssignment', data)
                    .then((response) => {
                      if (response.data.success == 'true') {
                        Swal.fire({
                          title: "Naloga odstranjena",
                          text: "Uspešno ste odstranili nalogo.",
                          icon: "success",
                          confirmButtonText: "Razumem",
                          confirmButtonColor: "#4377df"
                        })
                      } else {
                        Swal.fire({
                          title: "Napaka",
                          text: "Ojoj, prišlo je do nepričakovane napake! Poskusite ponovno ali kontaktirajte administratorja.",
                          icon: "error",
                          confirmButtonText: "Ti šment!",
                          confirmButtonColor: "#4377df"
                        })
                      }
                    })
              } else if (isSuccess.isDenied) {
                Swal.fire({
                  title: "Naloga ohranjena",
                  text: "Brez skrbi! Vaša prejšna naloga je na varnem.",
                  icon: "success",
                  confirmButtonText: "Fjuu, čudovito.",
                  confirmButtonColor: "#4377df"
                })
              }
            })
        this.$forceUpdate();
      }
    },
    created() {
      this.getGivAssignment();
    },
  },
}
</script>

<style scoped>
.given-assignments {
  height: 40vh;
  width: 82vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-left: 2vw;
  counter-reset: section;
}

.given-assignment {
  height: 2vh;
  width: 40vw;
  display: flex;
  flex-direction: row;
  justify-content: left;
  align-items: center;
  gap: 5vw;
  margin-bottom: 5vh;
  margin-left: 2vw;
  border-bottom: 4px solid #2e5baa;
  padding-bottom: 2vh;
  padding-top: 1vh;

}

.given-assignment:hover {
  border-bottom: 4px solid #5891d3;
  transition: 0.2s ease-in-out;
  cursor: pointer;
}

.given-assignment h2 {
  color: black;
  font-size: large;
  width: 15vw;
  line-break: auto;
}

.given-assignment h2, p {
  padding-top: 1vh;
  padding-bottom: 3vh;
}

.given-assignment h2::before {
  counter-increment: section;
  content: counter(section) ": ";
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

.assignment {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
  width: 100%;
  height: 100%;
  padding-top: 5vh;
  background: #dee8fb;
  border-top-left-radius: 20px;
  border-top-right-radius: 20px;
  box-shadow: -1px -1px 13px 1px #00000040
}

.button{
  display: flex;
  flex-direction: column;
  gap: 2vh;
  width: 100%;
  padding: 2vh;
}
</style>