<template>
  <div class="admin-teacher">
    <div class="tittle">
      <h1>Profesorji</h1>
    </div>
    <div class="classes">
      <div class="search-bar">
        <div>
          <input type="text" placeholder="Najdi profesorja po imenu ali priimku..." v-model="searchInput"  @change="checkIfSearch" >
        </div>
      </div>
      <div class="table">
        <div class="students-table">
          <div class="student-row" v-for="t in teachers">
            <div class="pwidth">
              <p>{{ t.name }}</p>
              <p>{{ t.surname }}</p>
              <p>{{ t.class }}</p>
            </div>
            <div class="ppic">
              <p @click="deleteTeacher(t.id)"><img src="../assets/delete-icon.png" alt="odstrani dijaka"></p>
              <p @click="editTeacher(t.id)"><img src="../assets/editing.png" alt="uredi dijaka"></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="button">
      <div class="button-add" @click="toTeacherAdd">
        Dodaj
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
      teachers: Array(),
      searchInput: ''
    }
  },
  methods: {
    getAllTeachers() {
      this.teachers = Array();
      let token = sessionStorage.getItem('token');
      if (token != null) {
        const jwt = new FormData();
        jwt.append('token', token);
        axios.post('https://smv.usdd.company/API/public/api/teacher/get-all', jwt)
            .then((response) => {
              if (response.data.teachers != null) {
                for (let i = 0; i < (response.data.teachers).length; i++) {
                  this.teachers.push(response.data.teachers[i]);
                  console.log(this.teachers)
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
    findTeacher() {
      this.teachers = Array()
      let token = sessionStorage.getItem('token');
      console.log(this.searchInput.length)
      if (token != null && this.searchInput) {
        const jwt = new FormData();
        jwt.append('token', token);
        jwt.append('search', this.searchInput)
        axios.post('https://smv.usdd.company/API/public/api/teacher/get-all', jwt)
            .then((response) => {
              if (response.data.teachers != null) {
                for (let i = 0; i < (response.data.teachers).length; i++) {
                  this.teachers.push(response.data.teachers[i]);
                }
              }
            })
      }

    },
    checkIfSearch(){
      if(this.searchInput.length > 0){
        this.findTeacher();
      } else {
        this.getAllTeachers();
      }
    },
    deleteTeacher(id: string){
      const token = sessionStorage.getItem('token');
      if (token != null && id != null){
        Swal.fire({
          title: 'Izbrišem profesorja?',
          text: 'Želite izbrisati profesorja iz sitema? Tega dejanja ni mogoče razveljaviti!',
          icon: "question",
          confirmButtonText: 'Da, izbriši profesorja',
          confirmButtonColor: '#e63946',
          showCancelButton: true,
          cancelButtonText: 'Ne, prekliči izbris',
          cancelButtonColor: '#4377df',
        })
            .then((event) => {
              if(event.isConfirmed){
                const data = new FormData();
                data.append('token', token)
                data.append('teacherId', id)
                axios.post('https://smv.usdd.company/API/public/api/teacher/delete', data)
                    .then((response) =>{
                      if(response.data.success == "true"){
                        Swal.fire({
                          title: 'Izbris uspešen',
                          text: 'Profesor uspešno izbrisan',
                          icon: 'success',
                          confirmButtonColor: '#4377df'
                        })
                            .then((event) => {
                              if (event.isConfirmed && event.isDismissed){
                                this.$router.push('/teachers')
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
    editTeacher(id: string){
      sessionStorage.setItem('currentTeacher', id)
      sessionStorage.setItem('currentType', 'teacher')
      this.$router.push('/user/edit')
    },
    toTeacherAdd(){
      this.$router.push('/teachers/add')
    }
  },
  created() {
    this.checkIfSearch()
  },
}
</script>

<style scoped>
.admin-teacher {
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
  height: 70vh;
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
.student-row p img{
  height: 3vh;
  cursor: pointer;
}

.button-add {
  background-color: #315cfd;
  border: none;
  color: white;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius: 5px;
  box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.25);
  position: relative;
  overflow: hidden;
  width: 20vw;
}

.button-add:after {
  content: "";
  background-color: rgba(255, 255, 255, 0.2);
  position: absolute;
  top: 50%;
  left: 50%;
  width: 5px;
  height: 5px;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  opacity: 0;
}

.button-add:hover:after {
  animation: ripple_401 1s ease-out;
}

@keyframes ripple_401 {
  0% {
    width: 5px;
    height: 5px;
    opacity: 1;
  }

  100% {
    width: 200px;
    height: 200px;
    opacity: 0;
  }
}

.button {
  display: flex;
  flex-direction: row;
  gap: 5vh;
  width: 82vw;
  padding-top: 5vh;
  margin-left: auto;
  margin-right: auto;
  justify-content: center;
  align-items: center;
}
.pwidth{
  display: flex;
  flex-direction: row;
  gap: 5vw;
}
.pwidth p{
  width: 8vw;
}
.ppic{
  display: flex;
  flex-direction: row;
  gap: 5vw;
}
</style>