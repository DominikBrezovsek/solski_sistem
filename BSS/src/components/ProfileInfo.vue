<template>
  <div class="ProfileInfo">
    <div class="tittle">
      <h1>Urejanje uporabniškega profila</h1>
    </div>
    <div class="user-data">
      <div class="flex flex-row user-data-row">
        <div class="inner-div">
          <label for="Name">Ime</label>
          <input type="text" name="Name" id="Name" v-model="name" @change="changed='true'">
        </div>
        <div class="inner-div">
          <label for="Surname">Priimek</label>
          <input type="text" name="Surname" id="Surname" v-model="surname" @change="changed='true'">
        </div>

      </div>
      <div class="flex flex-row user-data-row">
        <div class="inner-div">
          <label for="Email">E-poštni naslov</label>
          <input type="email" name="Email" id="Email" v-model="email" @change="changed='true'">
        </div>
        <div class="inner-div">
          <label for="Class">Razred</label>
          <input type="text" name="Class" id="Class" disabled v-model="razred" v-if="type == 'student'"
                 @change="changed='true'">
        </div>
      </div>
      <div class="button-div">
      <div class="button-save" @click="saveChanges">Shrani spremembe</div>
      </div>
    </div>
  </div>
</template>


<script lang="ts">
import axios from 'axios';
import Swal from "sweetalert2";

export default {
  data() {
    return {
      name: "",
      surname: "",
      razred: "",
      razredId: "",
      type: "",
      email: "",
      changed: ""
    }
  },
  methods: {
    getUser(tip: string) {
      const token = new FormData();
      const localToken = sessionStorage.getItem('token')
      if (localToken != null) {
        token.append('token', localToken)
      }
      switch (tip) {
        case "student": {
          this.type = tip;
          axios.post('https://smv.usdd.company/API/public/api/student/get', token)
              .then((response) => {
                if (response.data.name) {
                  this.name = response.data.name;
                  this.surname = response.data.surname;
                  this.razred = response.data.class;
                  this.email = response.data.email;
                  this.razredId = response.data.classId
                } else if (response.data.error) {
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
              }, (error) => {
                console.log(error);
              });
          break
        }
        case "teacher": {
          this.type = tip;
          axios.post('https://smv.usdd.company/API/public/api/teacher/get', token)
              .then((response) => {
                if (response.data.name != "") {
                  this.name = response.data.name;
                  this.surname = response.data.surname;
                  sessionStorage.setItem('teacherId', response.data.id);
                } else if (response.data.error == "token") {
                  sessionStorage.clear();
                  this.$router.push('/');
                }
              }, (error) => {
                console.log(error);
              });
          break
        }
        case "admin": {
          this.type = tip;
          axios.post('https://smv.usdd.company/API/public/api/admin/get', token)
              .then((response) => {
                this.name = response.data.name;
                this.surname = response.data.surname;
              }, (error) => {
                console.log(error);
              });
          break
        }
      }
    },
    saveChanges() {
      const path = "https://smv.usdd.company/API/public/api/"
      const token = sessionStorage.getItem('token');
      if (this.changed == 'true') {
        if (token != null) {
          const data = new FormData();
          data.append('token', token);
          data.append('name', this.name);
          data.append('surname', this.surname);
          data.append('email', this.email);
          data.append('classId', this.razredId)

          axios.post(path + 'student/update', data)
              .then((response) => {
                if (response.data.success == 'true') {
                  Swal.fire({
                    title: 'Podatki posodobljeni',
                    text: 'Podatki o uporabniku uspešno posodobljeni.',
                    icon: 'success',
                    confirmButtonText: 'Razumem',
                    confirmButtonColor: '#4377df'
                  })
                      .then((event) => {
                        if (event.isConfirmed){
                          this.changed = '';
                        }
                        else {
                          this.changed = '';
                        }
                      })
                } else {
                  Swal.fire({
                    title: 'Napaka',
                    text: 'Napaka pri posodabljanju podatkov. Prosim, poskusite kasneje.',
                    icon: 'error',
                    confirmButtonText: 'Razumem',
                    confirmButtonColor: '#4377df'
                  })
                }
              })
        }
      } else if (this.changed != 'true') {
        Swal.fire({
          title: 'Nespremenjeni podatki',
          text: 'Naredili niste nobene spremembe!',
          icon: 'warning',
          confirmButtonText: 'Razumem',
          confirmButtonColor: '#4377df'
        })
      }


    }
  },
  created() {
    const type = sessionStorage.getItem('type');
    if (type != null) {
      this.getUser(type);
    }
  }
}

</script>

<style scoped>
.ProfileInfo {
  display: flex;
  flex-direction: column;
  gap: 2vh;
  width: 82vw;
  height: 40vh;
  justify-content: flex-start;
  align-items: flex-start;
  padding-left: 2vw;
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

.user-data {
  display: flex;
  flex-direction: column;
  width: 100%;
  height: 100%;
  justify-content: space-evenly;
  align-items: center;
}

.user-data-row {
  gap: 5vw;
}

.user-data input {
  height: 6vh;
  width: 22vw;
  border: 2px solid #4377df;
  border-radius: 10px;
  color: #6e7881;
  padding-left: 0.75vw;
  outline: none;
  transition: all 0.15s ease-in-out;
}

.user-data input:hover {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  transition: all 0.15s ease-in-out;

}

.user-data input:active {
  outline: none;
}

.user-data input:focus {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  font-weight: 500;
  transition: all 0.15s ease-in;
}

.inner-div {
  display: flex;
  flex-direction: column;
  justify-content: space-evenly;
  align-items: flex-start;
  gap: 1vh;
}

.inner-div label {
  font-weight: bold;
  font-size: 1.2rem;
}

.button-save {
  padding: 2vh 2vw;
  border: 2px solid #4377df;
  border-radius: 10px;
  color: #6e7881;
  transition: all 0.15s ease-out;
}

.button-div{
  padding-top: 2vh;
}

.button-save:hover {
  padding: 1.80vh 1.80vw;
  border: 4px solid #4377df;
  border-radius: 10px;
  background: #4377df;
  color: white;
  font-weight: 500;
  transition: all 0.15s ease-in;
}
</style>