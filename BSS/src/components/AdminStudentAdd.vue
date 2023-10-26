<template>
  <div class="add-student">
    <div class="tittle">
      <h1>Dodaj dijaka</h1>
    </div>
    <div class="flex flex-row justify-center">
      <div class="student-form">
        <div class="student-input">
          <label for="username">Uporabniško ime</label>
          <input type="text" v-model="username" id="username" required>
        </div>
        <div class="student-input">
          <label for="password">Geslo</label>
          <input type="password" v-model="password" id="password" required>
        </div>
        <div class="student-input">
          <label for="ime">Ime dijaka</label>
          <input type="text" v-model="ime" id="ime" required>
        </div>
        <div class="student-input">
          <label for="priimek">Priimek dijaka</label>
          <input type="text" v-model="priimek" id="priimek" required>
        </div>
        <div class="student-input">
          <label for="email">E-poštni naslov</label>
          <input type="text" v-model="email" id="email" required>
        </div>
        <div class="student-input">
          <label for="razred">Šola</label>
          <select name="school" id="razred" v-model="currentSchool" @change="getClasses">
            <option value="" disabled selected>Izberi šolo</option>
            <option v-for="school in schools" :value="school.id">{{ school.name }}</option>
          </select>
        </div>
        <div class="student-input">
          <label for="razred">Razred</label>
          <select name="school" id="razred" v-model="razred" @change="getClasses">
            <option value="" disabled selected>Izberi razred</option>
            <option v-for="razred in razredi" :value="razred.id">{{ razred.class }}</option>
          </select>
        </div>
      </div>
      <div class="subject-select">
        <div class="option" v-for="s in subjects" >
          <label :for="s.id">{{s.subject}}</label>
          <input type="checkbox" :id="s.id" :value="s.id" v-model="selectedSubjects">
        </div>
      </div>
    </div>

    <div class="button">
      <label class="button-add" @click="createStudent">
        Dodaj dijaka
      </label>
    </div>
  </div>
</template>


<script  lang="ts">
import axios from "axios";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      username: '',
      password: '',
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
    createStudent() {
      const token = sessionStorage.getItem('token');
      console.log(token)
      if (token != null && this.username != null && this.password != null && this.ime != null && this.priimek != null
      && this.razred != null && this.email != null && this.selectedSubjects.length != 0) {
        const path = 'https://smv.usdd.company/API/public/api/'
        const data = new FormData();
        data.append('token', token)
        data.append('username', this.username)
        data.append('password', this.password)
        data.append('name', this.ime)
        data.append('surname', this.priimek)
        data.append('email', this.email)
        data.append('class', this.razred)
        data.append('subjects', JSON.stringify(this.selectedSubjects))
        axios.post(path + 'student/create', data)
            .then((response) => {
              if (response.data.success == "true") {
                Swal.fire({
                  title: 'Dijak ustvarjen',
                  text: 'Dijak je bil uspešno ustvarjen.',
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
.add-student {
  display: flex;
  flex-direction: column;
  gap: 2vh;
  width: 100%;
  padding-left: 2vw;
}

.add-student h2, p {
  padding: 2vh;
}

.add-student h2 {
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
  padding-top: 2vh;
  margin-left: auto;
  margin-right: auto;
}

.student-form {
  display: flex;
  flex-direction: column;
  gap: 3vh;
  padding-right: 10vw;
}

.student-form label {
  font-size: 1rem;
  font-weight: 500;
}

.student-input {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.student-input input, select {
  height: 6vh;
  width: 22vw;
  border: 2px solid #4377df;
  border-radius: 10px;
  color: #6e7881;
  padding-left: 0.75vw;
  outline: none;
  transition: all 0.15s ease-in-out;
}

.student-input input:hover, select:hover {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  transition: all 0.15s ease-in-out;

}

.student-input input:active, select:active {
  outline: none;
}

.student-input input:focus, select:focus {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  font-weight: 500;
  transition: all 0.15s ease-in;
}
</style>