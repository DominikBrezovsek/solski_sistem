<template>
  <div class="submitted-assignments">
    <div class="tittle">
      <h1>Oddane naloge</h1>
    </div>
    <div class="assignment">
      <div class="submitted-assignment" v-for ="a in assignment">
        <div @click="assignmentInfo(a.id)" class="assignment-inner-div">
          <h2>{{a.tittle}}</h2>
          <p>{{a.subject}}</p>
          <p>{{a.deadline}}</p>
        </div>
        <div class="button">
          <p><img src="../assets/delete-icon.png" alt="Izbriši" @click="deleteSubmission(a.file)"></p>
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
      assignment: Array()
    }
  },
  methods: {
    getSubAssignment() {
      let token = sessionStorage.getItem('token');
      const subjectId = sessionStorage.getItem('subjectId');
      if (token != null && subjectId != null) {
        const jwt = new FormData();
        jwt.append('token', token);
        jwt.append('subjectId', subjectId)
        axios.post('https://smv.usdd.company/API/public/api/assignment/submitted', jwt)
            .then((response) => {
              if (response.data != null) {
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
                }) .then((event) => {
                  if (event.isConfirmed){
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
      } else {
      }
    },
    assignmentInfo(id: string){
      sessionStorage.setItem('assignmentId', id);
      this.$router.push('/assignments');
    },
    deleteSubmission(file: string){
      const token = sessionStorage.getItem('token');
      const subjectId = sessionStorage.getItem('subjectId');
      if (token != null && subjectId != null){
        /*define the API URL*/
        const path = "https://smv.usdd.company/API/public/api/"
        /*Create new FormData instance and append the needed data*/
        const data = new FormData();
        data.append('token', token);
        data.append('subjectId', subjectId);
        data.append('fileName', file)

        /*show an alert-box that prevents user from accidentally deleting a submission*/

        Swal.fire({
          title: "Odstrani oddajo?",
          text: "Oddaja bo odstranjena iz sistema, datoteka pa izbrisana. Tega dejanja ni mogoče razveljaviti. Želite nadaljevati?",
          icon: "question",
          confirmButtonText: 'Da, odstrani oddajo',
          confirmButtonColor: "#e63946",
          showDenyButton: true,
          denyButtonText: 'Ne, zadovoljen sem s trenutno oddajo.',
          denyButtonColor: '#4377df'
        })
            .then(function (isSuccess){
              if(isSuccess.isConfirmed){
                axios.post(path + 'assignment/deleteSubmission', data)
                    .then((response) => {
                      if (response.data.success == 'true'){
                        Swal.fire({
                          title: "Oddaja odstranjena",
                          text: "Uspešno ste odstranili oddajo naloge.",
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
              } else if (isSuccess.isDenied){
                Swal.fire({
                  title: "Oddaja ohranjena",
                  text: "Brez skrbi! Vaša prejšna oddaja je na varnem.",
                  icon: "success",
                  confirmButtonText: "Fjuu, čudovito.",
                  confirmButtonColor: "#4377df"
                })
              }
            })
        this.$forceUpdate();
      }
    }
  },
  created() {
    this.getSubAssignment();
  },
}
</script>

<style scoped>
.submitted-assignments{
  height: 40vh;
  width: 82vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-left: 2vw;
  counter-reset: section;
}
.submitted-assignment{
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
.assignment-inner-div{
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: left;

}
.submitted-assignment:hover {
  border-bottom: 4px solid #5891d3;
  transition: 0.2s ease-in-out;
  cursor: pointer;
}
.submitted-assignment h2 {
  color: black;
  font-size: large;
  width: 15vw;
  line-break: auto;
}
.submitted-assignment img {
  width: 2vw;
}
.submitted-assignment img:hover,
.submitted-assignment img:focus{
   width:2.2vw;
 }
.submitted-assignment h2,p {
  padding-top: 1vh;
  padding-bottom: 3vh;
}
.submitted-assignment h2::before {
  counter-increment: section;
  content: counter(section) ": ";
}
.tittle{
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
.assignment{
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

</style>