<template>
  <div class="add-teacher">
    <div class="tittle">
      <h1>Uredi dijaka</h1>
    </div>
    <div class="content">
      <div class="teacher-form">
        <div class="teacher-input">
          <label for="ime">Ime</label>
          <input type="text" v-model="ime" id="ime" required>
        </div>
        <div class="teacher-input">
          <label for="priimek">Priimek</label>
          <input type="text" v-model="priimek" id="priimek" required>
        </div>
        <div class="teacher-input">
          <label for="email">E-poštni naslov</label>
          <input type="email" v-model="email" id="email" required>
        </div>
        <div class="teacher-input">
          <label for="razred">Šola</label>
          <select name="school" id="razred" v-model="currentSchool" @change="getClasses">
            <option value="" disabled selected>Izberi šolo</option>
            <option v-for="school in schools" :value="school.id">{{ school.name }}</option>
          </select>
        </div>
        <div class="teacher-input">
          <label for="razred">Razred</label>
          <select name="school" id="razred" v-model="razred" @change="getClasses">
            <option value="" disabled selected>Izberi razred</option>
            <option v-for="razred in razredi" :value="razred.id">{{ razred.class }}</option>
          </select>
        </div>
      </div>
      <div class="teacher-select">
        <div class="option" v-for="s in subjects">
          <label :for="s.id">{{s.subject}}</label>
          <input type="checkbox" :id="s.id" :value="s.id" v-model="selectedSubjects">
        </div>
      </div>
    </div>
    <div class="button">
      <label class="button-add" @click="editStudent">
        Uredi
      </label>
    </div>
  </div>
</template>

<script lang="ts">
import axios from "axios";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      ime: '',
      priimek: '',
      razred: '',
      email: '',
      razredi: Array(),
      subjects: Array(),
      selectedSubjects: Array(),
      currentSchool: '',
      schools: Array(),
    }
  },
  methods: {
    editStudent() {
      const token = sessionStorage.getItem('token');
      const studentId = sessionStorage.getItem('currentStudent')
      console.log(token)
      if (token != null  && this.ime != '' && this.priimek != '' &&
          this.email != '' && this.razred != '' && this.selectedSubjects.length != 0 && studentId != null) {
        const path = 'https://smv.usdd.company/API/public/api/'
        const data = new FormData();
        data.append('token', token)
        data.append('name', this.ime)
        data.append('surname', this.priimek)
        data.append('email', this.email)
        data.append('class', this.razred)
        data.append('subjects', JSON.stringify(this.selectedSubjects))
        data.append('studentId',studentId )
        axios.post(path + 'student/update', data)
            .then((response) => {
              if (response.data.success == "true") {
                Swal.fire({
                  title: 'Dijak posodobljen',
                  text: 'Dijak je bil uspešno posodobljen.',
                  icon: 'success',
                  confirmButtonText: 'Razumem',
                  confirmButtonColor: '#4377df'
                })
                    .then((event) => {
                      if (event.isConfirmed) {
                        this.$router.push('/students')
                      } else {
                        this.$router.push('/students')
                      }
                    })
              }
            })
      } else {
        Swal.fire({
          title: 'Majnkajoča polja',
          text: 'Prosim, izpolnite vsa polja.',
          icon: 'warning',
          confirmButtonText: 'Razumem',
          confirmButtonColor: '#4377df'
        })
      }
    },

    getStudent(){
      this.subjects = Array();
      let token = sessionStorage.getItem('token');
      const studentId = sessionStorage.getItem('currentStudent')
      if (token != null && studentId != null) {
        const jwt = new FormData();
        jwt.append('token', token);
        jwt.append('studentId', studentId)
        axios.post('https://smv.usdd.company/API/public/api/student/admin-get', jwt)
            .then((response) => {
              if (response.data.subjects != null && response.data.student != null) {
                console.log(response.data.subjects)
                this.ime = response.data.student.name
                this.priimek = response.data.student.surname
                this.email = response.data.student.email
                this.razred = response.data.student.classId
                for (let i = 0; i < (response.data.subjects).length; i++) {
                  this.selectedSubjects.push(response.data.subjects[i].id.toString());
                  console.log(this.selectedSubjects[i].id)
                }
              } else if (response.data.error == "token"){
                Swal.fire({
                  title: 'Seja je potekla',
                  text: 'Za nadaljevanje se ponovno prijavite.',
                  icon: "warning",
                  confirmButtonText: 'Prijava',
                  confirmButtonColor: '#4377df'
                }).then((event) => {
                  if (event.isConfirmed || event.isDismissed) {
                    sessionStorage.clear();
                    localStorage.clear();
                    this.$router.push('/');
                  }
                })
              }
            })
      }
    },
    getAllSubjects() {
      this.subjects = Array();
      let token = sessionStorage.getItem('token');
      if (token != null) {
        const jwt = new FormData();
        jwt.append('token', token);
        axios.post('https://smv.usdd.company/API/public/api/subjects/get-all', jwt)
            .then((response) => {
              if (response.data.subjects != null) {
                for (let i = 0; i < (response.data.subjects).length; i++) {
                  this.subjects.push(response.data.subjects[i]);
                  console.log(this.subjects)
                }
              }
            })
      }
    },
    getClasses() {
      const school = new FormData();

      school.append('school', this.currentSchool);
      axios.post('https://smv.usdd.company/API/public/api/school/classes', school)
          .then((response) => {
            if (response.data != null) {
              this.razredi = response.data;
            }
          }, (error) => {
            console.log(error);
          });
    }
  },
  created() {
    this.getAllSubjects();
    this.getStudent();
    axios.get('https://smv.usdd.company/API/public/api/school/getAll')
        .then((response) => {
          this.schools = response.data;
        }, (error) => {
          console.log(error);
        });
  },
}
</script>

<style scoped>
.add-teacher {
  display: flex;
  flex-direction: column;
  gap: 2vh;
  width: 100%;
  padding-left: 2vw;
}

.add-teacher h2, p {
  padding: 2vh;
}

.add-teacher h2 {
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
  cursor: pointer;
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
  flex-direction: column;
  gap: 2vh;
  width: 40%;
  padding-top: 5vh;
  margin-left: auto;
  margin-right: auto;
}

.teacher-form {
  display: flex;
  flex-direction: column;
  gap: 3vh;
}

.teacher-form label {
  font-size: 1rem;
  font-weight: 500;
}

.teacher-input {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.teacher-input input, select {
  height: 6vh;
  width: 22vw;
  border: 2px solid #4377df;
  border-radius: 10px;
  color: #6e7881;
  padding-left: 0.75vw;
  outline: none;
  transition: all 0.15s ease-in-out;
}

.teacher-input input:hover, select:hover {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  transition: all 0.15s ease-in-out;

}

.teacher-input input:active, select:active {
  outline: none;
}

.teacher-input input:focus, select:focus {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  font-weight: 500;
  transition: all 0.15s ease-in;
}
.content{
  display: flex;
  flex-direction: row;
  gap: 5vw;
  justify-content: center;
  align-items: center;
}
</style>