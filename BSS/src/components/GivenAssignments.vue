<template>
  <div class="given-assignments">
    <div class="tittle">
      <h1>Dodeljene naloge</h1>
    </div>
    <div class="assignment">
      <div class="given-assignment" v-for ="a in assignment" @click="assignmentInfo(a.id)">
        <h2>{{a.tittle}}</h2>
        <p>{{a.subject}}</p>
        <p>{{a.deadline}}</p>
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
    getGivAssignment() {
      let token = sessionStorage.getItem('token');
      const subject = sessionStorage.getItem('subjectId');
      if (token != null && subject != null) {
        const jwt = new FormData();
        jwt.append('token', token);
        jwt.append('subjectId', subject )
        axios.post('https://smv.usdd.company/API/public/api/assignment/get-all', jwt)
            .then((response) => {
              if (response.data != null) {
                for (let i = 0; i < (response.data.assignments).length; i++) {
                  this.assignment.push(response.data.assignments[i]);
                }
              } else if (response.data.error == "token"){
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
                  }
                })
              }
            })
      }
    },
    assignmentInfo(id: string){
      sessionStorage.setItem('assignmentId', id);
      this.$router.push('/assignments');
    }
  },
  created() {
    this.getGivAssignment();
  },
}
</script>

<style scoped>
.given-assignments{
  height: 40vh;
  width: 85vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  counter-reset: section;
}
.given-assignment{
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
.given-assignment h2,p {
  padding-top: 1vh;
  padding-bottom: 3vh;
}
.given-assignment h2::before {
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