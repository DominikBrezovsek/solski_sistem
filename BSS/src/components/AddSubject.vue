<template>
  <div class="add-subject">
    <div class="tittle">
      <h1>Dodaj predmet</h1>
    </div>
    <div class="subject-form">
      <div class="subject-input">
        <label for="predmet">Ime predmeta</label>
        <input type="text" v-model="title" id="predmet" required>
      </div>
      <div class="subject-input">
        <label for="Opis">Opis predmeta</label>
        <input type="text" v-model="description" id="Opis" required>
      </div>
    </div>
    <div class="button">
      <label class="button-add" @click="createSubject">
        Dodaj predmet
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
      title: '',
      description: ''
    }
},
  methods: {
    createSubject() {
      const token = sessionStorage.getItem('token');
      console.log(token)
      if (token != null && this.title != null && this.description != null) {
        const path = 'https://smv.usdd.company/API/public/api/'
        const data = new FormData();
        data.append('token', token)
        data.append('subject', this.title)
        data.append('description', this.description)
        axios.post(path + 'subjects/create', data)
            .then((response) => {
              if (response.data.success == "true") {
                Swal.fire({
                  title: 'Predmet ustvarjen',
                  text: 'Predmet je bil uspešno ustvarjen.',
                  icon: 'success',
                  confirmButtonText: 'Razumem',
                  confirmButtonColor: '#4377df'
                })
                    .then((event) => {
                      if (event.isConfirmed) {
                        this.$router.push('/classes')
                      } else {
                        this.$router.push('/classes')
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
    }
  }
}
</script>

<style scoped>
.add-subject {
  display: flex;
  flex-direction: column;
  gap: 2vh;
  width: 100%;
  padding-left: 2vw;
}

.add-subject h2, p {
  padding: 2vh;
}

.add-subject h2 {
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

.subject-form {
  display: flex;
  flex-direction: column;
  gap: 3vh;
  padding-top: 10vh;
}

.subject-form label {
  font-size: 1rem;
  font-weight: 500;
}

.subject-input {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.subject-input input, select {
  height: 6vh;
  width: 22vw;
  border: 2px solid #4377df;
  border-radius: 10px;
  color: #6e7881;
  padding-left: 0.75vw;
  outline: none;
  transition: all 0.15s ease-in-out;
}

.subject-input input:hover, select:hover {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  transition: all 0.15s ease-in-out;

}

.subject-input input:active, select:active {
  outline: none;
}

.subject-input input:focus, select:focus {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  font-weight: 500;
  transition: all 0.15s ease-in;
}
</style>