<template>
  <div class="login flex flex-row justify-between">
    <div class="about-side w-4/12 flex justify-center align-center">
      <div class="mt-auto mb-auto about-inner">
        <div class="tittle-about text-5xl font-bold">
          <h1>Welcome to</h1>
        </div>
        <div class="image-about ml-auto mr-auto">
          <img src="../assets/logo_BSS.png" alt="BSS logo"/>
        </div>
        <div class="text-3xl font-bold ml-auto mr-auto">
          <h1>BSS E-portal</h1>
        </div>
        <div class="text-xl font-normal ml-auto mr-auto text-about">
          <p>je moderna digitalna platforma, ki izboljšuje proces šolanja za dijake in profesorje.</p>
        </div>
      </div>
    </div>
    <div class="login-side w-8/12 flex flex-col justify-center">
      <div class="login-tittle flex flex-col">
        <div class="tittle ml-auto mr-auto text-5xl font-bold text-black">
          <h1>Ponastavi geslo</h1>
        </div>
        <div class="subtittle text-xl font-medium ml-auto mr-auto">
          Vnesite podatke za ponastavitev gesla.
        </div>
      </div>
      <div class="flex flex-col login-form w-full">
        <div class="flex flex-col">
          <label for="username" class="label">E-poštni naslov</label>
          <input id="username" class="username" type="text" v-model="email" placeholder="BabaJaga21"/>
        </div>
        <div class="flex flex-col">
          <label for="password" class="label">Novo geslo</label>
          <input id="password" class="password" type="password" v-model="password"
                 placeholder="Tvoje super skrivno geslo" @keyup.enter="pwdReset"/>
        </div>
        <div class="flex flex-col">
          <label for="password" class="label">Ponovi novo geslo</label>
          <input id="password" class="password" type="password" v-model="rePassword"
                 placeholder="Tvoje super skrivno geslo" @keyup.enter="pwdReset"/>
        </div>
        <div>
          <button @click="pwdReset" class="login-button">Ponastavi geslo</button>
        </div>
      </div>
      <div class="no-account">
        <p>Že imaš račun?
          <RouterLink to="/">Prijavi se</RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {RouterLink, RouterView} from 'vue-router';
import axios from 'axios';
import Swal from "sweetalert2";

export default {
  data() {
    return {
      email: "",
      password: "",
      rePassword: ""
    }
  },
  methods: {
    checkCriteria() {
      let input = this.password
      const uppercase = /[A-Z]/g;
      console.log(uppercase)
      const lowercase = /[a-z]/g;
      const special = /[^\w\s]/;
      const length = input.length;

      if (input.match(uppercase) && input.match(lowercase) && input.match(special) && length >= 8) {
        return true
      } else {
        return false
      }
    },
    pwdReset() {
      const credentials = new FormData();
      credentials.append('email', this.email);
      credentials.append('password', this.password);
      if (this.email != "" && this.password != "") {
        if (this.password == this.rePassword && this.password != '') {
          if (this.checkCriteria()) {
            axios.post('https://smv.usdd.company/API/public/api/login/pwdreset', credentials)
                .then((response) => {
                  if (response.data.success == "true") {
                    Swal.fire({
                      title: "Potrdite ponastavitev gesla",
                      text: "Na naveden e-poštni naslov ste prejeli povezavo za potrditev novega gesla. Geslo bo ponastavljeno," +
                          "ko boste to s klikom na link potrdili.",
                      icon: "success",
                      confirmButtonText: "Razumem",
                      buttonsStyling: true,
                      confirmButtonColor: "#4377df"
                    })
                        .then((event) => {
                          if (event.isConfirmed) {
                            this.$router.push('/');
                          } else {
                            this.$router.push('/');
                          }
                        })
                  }
                })
          } else {
            Swal.fire({
              title: "Geslo ni ustrezno!",
              text: "Vnešeno geslo ne zadošča pogojem. Vsebovati mora najmanj 8 znakov, 1 veliko črko in en poseben znak.",
              icon: "error",
              confirmButtonText: "Razumem",
              buttonsStyling: true,
              confirmButtonColor: "#4377df"
            })
          }
        } else {
          Swal.fire({
            title: "Neujemajoči gesli",
            text: "Vnešeni gesli se ne ujemata!",
            icon: "error",
            confirmButtonText: "Razumem",
            buttonsStyling: true,
            confirmButtonColor: "#4377df"
          })
        }
      } else {
        Swal.fire({
          title: "Manjkajoči podatki!",
          text: "Prosimo, izpolnite vsa polja.",
          icon: "error",
          confirmButtonText: "Razumem",
          buttonsStyling: true,
          confirmButtonColor: "#4377df"
        })
      }
    }
  }
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.login {
  width: 100vw;
  height: 100vh;
  left: 0;
}

.login-tittle {
  gap: 2vh;
  margin-top: 5vh;

}

.subtittle {
  text-align: center;
  margin-left: auto;
  margin-right: auto;
  width: 90%;
}

.login-form {
  margin-left: auto;
  margin-right: auto;
  width: 50vw;
  justify-items: center;
  align-items: center;
  margin-top: auto;
  margin-bottom: auto;

}

.login-side {
  justify-content: center;
  align-content: center;

}


.username,
.password {
  width: 35vw;
  height: 5vh;
  border: 1px solid black;
  border-radius: 5px;
  margin-bottom: 2vh;
  padding: 1vw;
}

.password::placeholder,
.username::placeholder {
  font-size: 1.5vh;
}

.login-button {
  width: 25vw;
  height: 5vh;
  border: none;
  border-radius: 10px;
  margin-top: 2vh;
  background-color: #4377df;
  color: white;
  font-size: 2vh;
}

.login-button:hover {
  background-color: #2e5baa;
}

.no-account {
  font-size: 2vh;
  margin-left: 10vw;
  margin-bottom: auto;
  margin-top: -15vh;
}

.no-account a {
  color: #4377df;
}

.no-account a:hover {
  color: #2e5baa;
}

.label {
  font-size: 2vh;
  margin-bottom: 0.5vh;
  margin-right: auto;
}

.about-side {
  width: 100vh;
  background: rgb(24, 37, 84);
  background: linear-gradient(185deg, rgba(24, 37, 84, 1) 0%, rgba(40, 100, 180, 1) 40%, rgba(117, 171, 228, 1) 100%);
}

.about-inner {
  width: 80%;
  height: 80vh;
  color: white;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.tittle-about {
  margin-left: auto;
  margin-right: auto;
}


.image-about {
  width: 50%;

}

.text-about {
  width: 80%;
  text-align: center;
}

</style>

