<template>
  <div class="AddAssignment">
    <div class="tittle">
      <h1>Dodaj nalogo</h1>
    </div>
    <div class="flex flex-col">
      <div class="assignment-form">
        <div class="assignment-form-inner">
          <div class="assignment-input">
            <label for="Naslov">Naslov naloge</label>
            <input type="text" v-model="title" id="Naslov" required>
          </div>
          <div class="assignment-input">
            <label for="Opis">Opis</label>
            <input type="text" v-model="description" id="Opis" required>
          </div>
        </div>
        <div class="assignment-form-inner">
          <div class="assignment-input">
            <label for="Predmet">Predmet</label>
            <select id="Predmet" v-model="currentSubject">
              <option name="subject-option" disabled>Naloga za predmet...</option>
              <option name="subject-option" v-for="s in Subjects" :value="s.id">{{ s.subject }}</option>
            </select>
          </div>
          <div class="assignment-input">
            <label for="Rok">Rok oddaje</label>
            <input id="Rok" type="datetime-local" v-model="deadline">
          </div>
        </div>
        <div class="assignment-form-inner">
            <label class="button-file">
              <input type="file" @change="storeFile" id="file"/>
              Naloži datoteko
            </label>
            <div class="curent-file">
              <p v-if="file != null">
                {{ fileName }}
              </p>
              <p v-else-if="file == null">
                Nobena datoteka ni izbrana!
              </p>
            </div>
          </div>
      </div>
      <div class="button">
        <label class="button-add" @click="addAssignment">
          Ustvari novo nalogo
        </label>
      </div>

    </div>
  </div>
</template>

<script lang="ts">
import axios from "axios";
import Swal from "sweetalert2";
import Moment from 'moment'
import moment from "moment";

export default {
  data() {
    return {
      title: null,
      description: null,
      currentSubject: null,
      Subjects: Array(),
      deadline: null,
      fileName: null,
      file: null
    }
  },
  methods: {
    storeFile(event: any) {
      this.file = event.target.files[0]
      if (this.file != null) {
        this.fileName = (event.target.files[0].name)
      }
    },
    getTeacherSubject() {
      const token = sessionStorage.getItem('token');
      console.log(token)
      if (token != null) {
        const path = 'https://smv.usdd.company/API/public/api/'
        const data = new FormData();
        data.append('token', token)
        axios.post(path + 'ts/get', data)
            .then((response) => {
              if (response.data.subjects != null) {
                for (var i = 0; i < response.data.subjects.length; i++) {
                  this.Subjects.push(response.data.subjects[i])
                }
              } else if (response.data.error == 'token') {
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
                });
              }
            })
      }
    },
    addAssignment() {
      console.log(this.deadline);
      const token = sessionStorage.getItem('token');
      if (token != null && this.file != null) {
        const path = 'https://smv.usdd.company/API/public/api/'
        const data = new FormData();
        data.append('token', token);
        if (this.currentSubject != null && this.file != null && this.title != null && this.description != null && this.deadline != null) {
          data.append('subjectId', this.currentSubject);
          data.append('material', this.file);
          data.append('title', this.title);
          data.append('desc', this.description);
          data.append('deadline', this.deadline);

          axios.post(path + 'assignment/create', data)
              .then((response) => {
                if (response.data.success == "true") {
                  Swal.fire({
                    title: 'Naloga ustvarjena',
                    text: 'Naloga je bila uspešo ustvarjena.',
                    icon: 'success',
                    confirmButtonText: 'Razumem',
                    confirmButtonColor: '#4377df'
                  })
                } else if (response.data.error == 'token') {
                  Swal.fire({
                    title: 'Seja je potekla',
                    text: 'Za nadaljevanje se ponovno prijavite.',
                    icon: "warning",
                    confirmButtonText: 'Prijava',
                    confirmButtonColor: '#4377df'
                  })
                }
              })
        } else {
          Swal.fire({
            title: 'Majnkajoča polja',
            text: 'Prosim, izpolnite vsa polja..',
            icon: 'warning',
            confirmButtonText: 'Razumem',
            confirmButtonColor: '#4377df'
          })
        }
      } else {
        Swal.fire({
          title: 'Majnkajoča polja',
          text: 'Prosim, izpolnite vsa polja..',
          icon: 'warning',
          confirmButtonText: 'Razumem',
          confirmButtonColor: '#4377df'
        })
      }
    }
  },
  mounted() {
    this.getTeacherSubject();
  },
}
</script>

<style scoped>
.AddAssignment {
  display: flex;
  flex-direction: column;
  gap: 2vh;
  width: 100%;
}

.AddAssignment h2, p {
  padding: 2vh;
}

.AddAssignment h2 {
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

.assignment-form {
  display: flex;
  flex-direction: column;
  gap: 3vh;
  padding-top: 10vh;
}

.assignment-form label {
  font-size: 1rem;
  font-weight: 500;
}

.assignment-input {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.assignment-input input, select {
  height: 6vh;
  width: 22vw;
  border: 2px solid #4377df;
  border-radius: 10px;
  color: #6e7881;
  padding-left: 0.75vw;
  outline: none;
  transition: all 0.15s ease-in-out;
}

.assignment-input input:hover, select:hover {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  transition: all 0.15s ease-in-out;

}

.assignment-input input:active, select:active {
  outline: none;
}

.assignment-input input:focus, select:focus {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  font-weight: 500;
  transition: all 0.15s ease-in;
}

.assignment-form-inner {
  display: flex;
  flex-direction: row;
  gap: 3vw;
  align-items: center;
  justify-content: center;
}

.button-file input[type='file']{
  display: none;
}

.button-file {
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

.button-file:after {
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

.button-file:hover:after {
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
</style>