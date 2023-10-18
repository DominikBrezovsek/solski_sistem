<template>
  <div class="admin-student">
    <div class="tittle">
      <h1>Dijaki</h1>
    </div>
    <div class="classes">
      <div class="search-bar">
        <div>
          <input type="text" placeholder="Najdi dijaka po imenu, priimku ali razredu..." v-model="searchInput"  @change="checkIfSearch" >
        </div>
      </div>
      <div class="table">
        <div class="students-table">
          <div class="student-row" v-for="s in students">
            <p>{{ s.name }}</p>
            <p>{{ s.surname }}</p>
            <p>{{ s.class }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script  lang="ts">
import axios from "axios";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      students: Array(),
      searchInput: ''
    }
  },
  methods: {
    getAllStudents() {
      this.students = Array();
      let token = sessionStorage.getItem('token');
      if (token != null) {
        const jwt = new FormData();
        jwt.append('token', token);
        axios.post('https://smv.usdd.company/API/public/api/student/get-all', jwt)
            .then((response) => {
              if (response.data.students != null) {
                for (let i = 0; i < (response.data.students).length; i++) {
                  this.students.push(response.data.students[i]);
                  console.log(this.students)
                }
              } else if (response.data.error == "token") {
                Swal.fire({
                  title: 'Seja je potekla',
                  text: 'Za nadaljevanje se ponovno prijavite.',
                  icon: "warning",
                  confirmButtonText: 'Prijava',
                  confirmButtonColor: '#4377df'
                })
                    .then((event) => {
                      sessionStorage.clear()
                      localStorage.clear()
                      this.$router.push('/')
                    })
              }
            })
      }
    },
    findStudent() {
      this.students = Array()
      let token = sessionStorage.getItem('token');
      console.log(this.searchInput.length)
      if (token != null && this.searchInput) {
        const jwt = new FormData();
        jwt.append('token', token);
        jwt.append('search', this.searchInput)
        axios.post('https://smv.usdd.company/API/public/api/student/get-all', jwt)
            .then((response) => {
              if (response.data.students != null) {
                for (let i = 0; i < (response.data.students).length; i++) {
                  this.students.push(response.data.students[i]);
                }
              }
            })
      }

    },
    checkIfSearch(){
      if(this.searchInput.length > 0){
        this.findStudent();
      } else {
        this.getAllStudents();
      }
    }
  },
  created() {
    this.checkIfSearch()
  },
}
</script>

<style scoped>
.admin-student {
  height: 10vh;
  width: 82vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  padding-left: 2vw;
}
.search-bar{
  width: 100%;
  height: 5vh;
  margin-bottom: 3vh;
  padding: 1vh 1vw;
}

.search-bar input{
  width: 100%;
  height: 100%;
  outline: none;
}

.search-bar input::placeholder{
  color: #6e7881;
}

.search-bar input::placeholder:focus{
  color: #000;
}
.search-bar input:focus{
  border-bottom: 3px solid #4377df;
  transition: all 0.15s ease-in;
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

.table {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
  width: 82vw;
  height: 80vh;
  padding-top: 5vh;
  background: #dee8fb;
  border-top-left-radius: 20px;
  border-top-right-radius: 20px;
  box-shadow: -1px -1px 13px 1px #00000040
}

.students-table {
  width: 80%;
  display: flex;
  flex-direction: column;
  justify-content: left;
  align-items: center;
  margin-bottom: 5vh;
  margin-left: 2vw;
  padding-bottom: 2vh;
  padding-top: 1vh;
  overflow-y: auto;
  gap: 3vh;
}

.student-row{
  border-bottom: 4px solid #2e5baa;
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 5vw;
  width: 100%;
  height: 5vh;
}

.student-row:hover {
  border-bottom: 4px solid #5891d3;
  transition: 0.2s ease-in-out;
  cursor: pointer;
}
</style>