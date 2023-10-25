<template>
  <div class="enroll">
    <div class="tittle">
      <h1>Dodaj predmet</h1>
    </div>
    <div class="enroll-form">
      <div>
        <select v-model="selectedSubject">
          <option disabled selected>Izberi predmet s seznama...</option>
          <option v-for="s in subjects" :value="s.id">{{ s.subject }}</option>
        </select>
      </div>
      <div class="button">
        <label class="button-add" @click="enrollStudent">
          Dodaj predmet
        </label>
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
      subjects: Array(),
      selectedSubject: '',
    }
  },
  methods: {
    enrollStudent(){
      const token = sessionStorage.getItem('token')
      if (token != null && this.selectedSubject != null){
        const data = new FormData();
        data.append('token', token);
        data.append('subjectId', this.selectedSubject)
        console.log(this.selectedSubject);
        axios.post('https://smv.usdd.company/API/public/api/sts/add', data)
            .then((response) => {
              if(response.data.success == "true"){
                Swal.fire({
                  title: 'Predmet dodan',
                  text: 'Predmet je bil uspešno dodan',
                  icon: 'success',
                  confirmButtonColor: '#4377df',
                  confirmButtonText: 'Razumem',
                })
                    .then((event) => {
                      if (event.isDismissed || event.isConfirmed){
                        Swal.close();
                        this.$router.push('/classes')
                      }
                    })
              } else if (response.data.error == 'enrolled') {
                Swal.fire({
                  title: 'Predmet že dodan',
                  text: 'Predmet je že na vašem seznamu predmetov!',
                  icon: 'warning',
                  confirmButtonColor: '#4377df',
                  confirmButtonText: 'Razumem',
                })
                    .then((event) => {
                      if (event.isDismissed || event.isConfirmed){
                        Swal.close();
                        this.$router.push('/classes')
                      }
                    })
              }
            })
      }
    },
    getAllSubjects(){
      let token = sessionStorage.getItem('token');
      if (token != null) {
        const jwt = new FormData();
        jwt.append('token', token);
        axios.post('https://smv.usdd.company/API/public/api/subjects/get-all', jwt)
            .then((response) => {
              if (response.data.subjects != null) {
                for (let i = 0; i < (response.data.subjects).length; i++) {
                  this.subjects.push(response.data.subjects[i]);
                }
              }
            });
      }
    }
  },
  created() {
    this.getAllSubjects();
},
}

</script>
<style scoped>
.enroll{
  height: 35vh;
  width: 82vw;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
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

.enroll-form {
  display: flex;
  flex-direction: column;
  gap: 3vh;
  padding-top: 10vh;
}

.subject-form label {
  font-size: 1rem;
  font-weight: 500;
}

.enroll-form input, select {
  height: 6vh;
  width: 22vw;
  border: 2px solid #4377df;
  border-radius: 10px;
  color: #6e7881;
  padding-left: 0.75vw;
  outline: none;
  transition: all 0.15s ease-in-out;
}

.enroll-form input:hover, select:hover {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  transition: all 0.15s ease-in-out;

}

.enroll-form input:active, select:active {
  outline: none;
}

.enroll-form input:focus, select:focus {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  font-weight: 500;
  transition: all 0.15s ease-in;
}
</style>