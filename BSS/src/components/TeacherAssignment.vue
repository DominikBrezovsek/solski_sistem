<template>
  <div class="given-assignments">
    <div class="tittle">
      <h1>Dodeljene naloge</h1>
    </div>
    <div class="assignment">
      <div class="given-assignment" v-for="a in assignment">
        <h2 @click="editAssignment(a.id)">{{ a.tittle }}</h2>
        <p  @click="editAssignment(a.id)">{{ a.subject }}</p>
        <p  @click="editAssignment(a.id)">{{ a.deadline }}</p>
        <img src="../assets/delete-icon.png" alt="delete" @click="deleteAssignment(a.id)">
        <img src="../assets/editing.png" @click="editAssignment(a.id)" alt="edit">
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
      const  token = sessionStorage.getItem('token');
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
    editAssignment(id: string) {
      sessionStorage.setItem('assignmentId', id);
      this.$router.push('/assignment/edit');
    },
    deleteAssignment(id:string){
      const  token = sessionStorage.getItem('token');
      const subject = sessionStorage.getItem('subjectId');
      if (token != null && subject != null && id != null){
        Swal.fire({
          title: 'Izbrišem nalogo?',
          text: 'Želite izbrisati nalogo? Tega dejanja ni mogoče razveljaviti!',
          icon: "question",
          confirmButtonText: 'Da, želim izbrisati nalogo',
          confirmButtonColor: '#4377df',
          showCancelButton: true,
          cancelButtonText: 'Ne, ohrani nalogo',
          cancelButtonColor: '#e63946'
        }) .then((event) => {
          if (event.isConfirmed){
            const path = 'https://smv.usdd.company/API/public/api/';
            const data = new FormData();
            data.append('token', token);
            data.append('subjectId', subject);
            data.append('assignmentId', id);
            axios.post(path + 'assignment/delete', data)
                .then((response) => {
                  if (response.data.success == 'true'){
                    Swal.fire({
                      title: 'Naloga izbrisana',
                      text: 'Naloga je bila uspešno izbrisana.',
                      icon: 'success',
                      confirmButtonText: 'Razumem',
                      confirmButtonColor: '#4377df'
                    })
                  } else if (response.data.error == "hasSubs"){
                    Swal.fire({
                      title: 'Naloga ima oddaje',
                      text: 'Naloga, ki jo želite izbrisati že ima oddaje! Izbris naloge bo odstranil tudi oddaje! Želite nadaljevati?',
                      icon: "question",
                      confirmButtonText: 'Da, želim izbrisati nalogo',
                      confirmButtonColor: '#4377df',
                      showCancelButton: true,
                      cancelButtonText: 'Ne, ohrani nalogo',
                      cancelButtonColor: '#e63946'
                    }) .then((event) => {
                      if (event.isConfirmed){
                        const path = 'https://smv.usdd.company/API/public/api/';
                        const data = new FormData();
                        data.append('token', token);
                        data.append('subjectId', subject);
                        data.append('assignmentId', id);
                        data.append('evenIf', "true")
                        axios.post(path + 'assignment/delete', data)
                            .then((response) => {
                              if (response.data.success == "true"){
                                Swal.fire({
                                  title: 'Naloga izbrisana',
                                  text: 'Naloga je bila uspešno izbrisana.',
                                  icon: 'success',
                                  confirmButtonText: 'Razumem',
                                  confirmButtonColor: '#4377df'
                                }) .then((event) => {
                                  if (event.isConfirmed || event.isDismissed){
                                    Swal.close()
                                    this.$router.push('/classes')
                                  }
                                })
                              }
                            })
                      }
                    })
                  }
                })
          } else {
            Swal.close();
          }
        })
      }
    }
  },
  created() {
    this.getGivAssignment();
  },
}
</script>

<style scoped>
.given-assignments {
  height: 60vh;
  width: 82vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-left: 2vw;
  counter-reset: section;
}

.given-assignment {
  height: 3vh;
  width: 60vw;
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

.given-assignment img {
  height: 4vh;
  padding-bottom: 0.5vh;
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
  padding-bottom: 5vh;
  margin-left: 1vh;
  display: flex;
  justify-content: center;
  color: grey;
  font-size: xx-large;
  flex-direction: column;
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
</style>