<template xmlns="http://www.w3.org/1999/html">
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
    <div class="tittle">
      <h1>Nastavi novo geslo</h1>
    </div>
    <div class="password-reset">
      <div class="pwd-reset-form">
        <div class="pwd-input">
        <label for="password">Novo geslo</label>
        <input :type="inputType" id="password" placeholder="Tvoje skrivno geslo" v-model="password" @keyup="checkCriteria">
        <div v-if="criteriaMet == 'false'" class="pwd-message">
          <h5>Geslo mora vsebovati</h5>
          <ul>
            <div class="criteria">
              <p v-if="uppercaseMet == 'true'" class="okay"><img src="../assets/check2.svg" alt="check-logo">Najmanj 1 veliko črko</p>
              <p v-else-if="uppercaseMet != 'true'" class="wrong"><img src="../assets/x.svg" alt="x-logo">Najmanj 1 veliko črko</p>
            </div>
            <div class="criteria">
              <p v-if="lowercaseMet == 'true'" class="okay"><img src="../assets/check2.svg" alt="check-logo">Najmanj 1 majhno črko</p>
              <p v-else-if="lowercaseMet != 'true'" class="wrong"><img src="../assets/x.svg" alt="x-logo">Najmanj 1 majhno črko</p>
            </div>
            <div class="criteria">
              <p v-if="specialSymbolMet == 'true'" class="okay"><img src="../assets/check2.svg" alt="check-logo">Najmanj 1 poseben znak</p>
              <p v-else-if="specialSymbolMet != 'true'" class="wrong"><img src="../assets/x.svg" alt="x-logo">Najmanj 1 poseben znak</p>
            </div>
            <div class="criteria">
              <p v-if="lengthMet == 'true'" class="okay"><img src="../assets/check2.svg" alt="check-logo">Najmanj 8 znakov</p>
              <p v-else-if="lengthMet != 'true'" class="wrong"><img src="../assets/x.svg" alt="x-logo">Najmanj 8 znakov</p>
            </div>
          </ul>
        </div>
        </div>
        <div class="pwd-input">
        <label for="repassword">Ponovno vnesi geslo</label>
        <input type="password" id="repassword" placeholder="Tvoje skrivno geslo" v-model="repassword">
        </div>
      </div>
      <div class="button-save" @click="resetPassword">Nastavi novo geslo</div>
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
      changed: "",
      inputType: "password",
      password: "",
      repassword: "",
      criteriaMet: 'false',
      uppercaseMet: 'false',
      lowercaseMet: 'false',
      specialSymbolMet: 'false',
      lengthMet: 'false',
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
    checkCriteria() {
      let input = this.password
      const uppercase = /[A-Z]/g;
      const lowercase = /[a-z]/g;
      const special = /[^\w\s]/;
      const length = input.length;
        if (input.match(uppercase)) {
          this.uppercaseMet = 'true';
        } else {
          this.uppercaseMet = 'false';
        }
        if (input.match(lowercase)) {
          this.lowercaseMet = 'true';
        } else {
          this.lowercaseMet = 'false';
        }
        if (input.match(special)) {
          this.specialSymbolMet = 'true';
        } else {
          this.specialSymbolMet = 'false';
        }
        if (length >= 7) {
          this.lengthMet = 'true';
        } else {
          this.lengthMet = 'false';
        }
      if (this.uppercaseMet == 'true' && this.lowercaseMet == 'true' && this.specialSymbolMet == 'true' && this.lengthMet == 'true') {
        this.criteriaMet = 'true'
      } else {
        this.criteriaMet = 'false'
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
                        if (event.isConfirmed) {
                          this.changed = '';
                        } else {
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


    },
    resetPassword() {
        const path = "https://smv.usdd.company/API/public/api/"
        const token = sessionStorage.getItem('token');
        if (this.password != '') {
          if (this.password == this.repassword){
            if (token != null) {
              const data = new FormData();
              data.append('token', token)
              data.append('password', this.password)

              axios.post(path + 'student/password', data)
                  .then((response) => {
                    if (response.data.success == 'true') {
                      Swal.fire({
                        title: 'Geslo spremenjeno',
                        text: 'Geslo je bilo uspešno spremenjeno.',
                        icon: 'success',
                        confirmButtonText: 'Razumem',
                        confirmButtonColor: '#4377df'
                      })
                          .then((event) => {
                            if (event.isConfirmed) {
                              this.password = '';
                              this.repassword = '';
                            } else {
                              this.password = '';
                              this.repassword = '';
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
          } else {
            Swal.fire({
              title: 'Gesli se ne ujemata',
              text: 'Gesli se ne ujemata!',
              icon: 'warning',
              confirmButtonText: 'Razumem',
              confirmButtonColor: '#4377df'
            })
          }

    } else {
            Swal.fire({
              title: 'Manjkajoči podatki',
              text: 'Prosim, izpolnite vnosna polja!',
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
  height: 100%;
  justify-content: flex-start;
  align-items: flex-start;
  padding-left: 2vw;
}

.tittle {
  width: 100%;
  padding: 2vh 0vw;
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
  gap: 2vh;
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

.button-div {
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

.password-reset{
  width: 82vw;
  height: 30vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding-bottom: 5vh;
}
.pwd-reset-form {
  display: flex;
  width: 50%;
  height: 100%;
  flex-direction: row;
  align-items: center;
  justify-content: space-evenly;
}

.pwd-reset-form input {
  height: 6vh;
  width: 10vw;
  border: 2px solid #4377df;
  border-radius: 10px;
  color: #6e7881;
  padding-left: 0.75vw;
  outline: none;
  transition: all 0.15s ease-in-out;
}

.pwd-reset-form input:hover {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  transition: all 0.15s ease-in-out;

}

.pwd-reset-form input:active {
  outline: none;
}

.pwd-reset-form input:focus {
  border: 4px solid #4377df;
  border-radius: 10px;
  color: black;
  padding-left: 0.65vw;
  font-weight: 500;
  transition: all 0.15s ease-in;
}

.criteria{
  display: flex;
  flex-direction: row;
}

.criteria p{
  display: flex;
  flex-direction: row;
}

.okay {
  color: #50d165;
  font-weight: 500;
  transition: all 0.15s ease-in;
}

.wrong {
  color: #d1404d;
  font-weight: 500;
  transition: all 0.15s ease-in;
}

.pwd-input{
  display: flex;
  flex-direction: column;
}
.pwd-message{
  position: absolute;
  z-index: 1;
  margin: 10vh -1vw;
  background: rgb(191 212 255 / 94%);
  border-radius: 10px;
  padding: 1vh 1vw;
}

.password-reset .button-save {
  margin-bottom: -15vh;
  margin-top: 10vh;
}
</style>