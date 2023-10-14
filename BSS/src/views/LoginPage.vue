<template>
  <CustomAlert/>
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
          <p>BSS is a modern digital platform dedicated to enchancing the educational journey of students and
            teachers
            alike.</p>
        </div>
      </div>
    </div>
    <div class="login-side w-8/12 flex flex-col justify-center">
      <div class="login-tittle flex flex-col">
        <div class="tittle ml-auto mr-auto text-5xl font-bold text-black">
          <h1>Login</h1>
        </div>
        <div class="subtittle text-xl font-medium ml-auto mr-auto">
          Enter your credentials to log into your account.
        </div>
      </div>
      <div class="flex flex-col login-form w-full">
        <div class="flex flex-col">
          <label for="username" class="label">Username</label>
          <input id="username" class="username" type="text" v-model="username" placeholder="BabaJaga21"/>
        </div>
        <div class="flex flex-col">
          <label for="password" class="label">Password</label>
          <input id="password" class="password" type="password" v-model="password"
                 placeholder="Your super secret password" @keyup.enter="login"/>
        </div>
        <div>
          <button @click="login" class="login-button">Login</button>
        </div>
      </div>
      <div class="no-account">
        <p>Don't have an account?
          <RouterLink to="/register2">Register</RouterLink>
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
      username: "",
      password: ""
    }
  },
  methods: {
    login() {
      const credentials = new FormData();
      credentials.append('username', this.username);
      credentials.append('password', this.password);
      if (this.username != "" && this.password != "") {
        axios.post('https://smv.usdd.company/API/public/api/login/check', credentials)
            .then((response) => {
              console.log(response.data.logged);
              if (response.data.success == "true") {
                sessionStorage.setItem('token', response.data.jwt);
                if (response.data.type != null) {
                  sessionStorage.setItem('type', response.data.type);
                }
                this.$router.push('/home');
              } else if (response.data.error == "credentials") {
                Swal.fire({
                  title: "Napaka pri prijavi",
                  text: "Napačno uporabniško ime ali geslo!",
                  icon: "error",
                  confirmButtonText: "Razumem",
                  buttonsStyling: true,
                  confirmButtonColor: "#4377df"
                })
              }
            }, (error) => {
              console.log(error);
              Swal.fire({
                title: "Napaka pri prijavi",
                text: "Prišlo je do napake na naši strani. Prosimo, poskusite kasneje ali kontakrirajte administratorja.",
                icon: "error",
                confirmButtonText: "Razumem",
                buttonsStyling: true,
                confirmButtonColor: "#4377df"
              });
            });
      } else {
        Swal.fire({
          title: "Manjkajoči podatki",
          text: "Prosimo, izpolnite vsa polja!",
          icon: "warning",
          confirmButtonText: "Razumem",
          buttonsStyling: true,
          confirmButtonColor: "#4377df"
        })
      }
    }
  },
  created() {
    localStorage.clear();
    sessionStorage.clear();
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

.confirm-button {
  background-color: #4377df;
  color: white;
  padding: 2vh;
  border-radius: 10px;
}
</style>

