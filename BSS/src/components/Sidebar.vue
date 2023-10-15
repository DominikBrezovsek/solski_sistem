<template>
  <div class="navbar">
    <div class="logo">
      <div>
        <img src="../assets/logo_BSS.png" alt="logo">
      </div>
      <div class="solsko-leto">
        <p>Šolsko leto {{ school_year }}</p>
      </div>
    </div>
    <div class="menu">
      <div class="options">
          <div @click="logout" class="logout">
            <div>
              <img src="../assets/logout-white.png" alt="logout">
            </div>
            <div>
              <h2>Odjava</h2>
            </div>
          </div>
        <div class="student" v-if="type == 'student'">
          <router-link to="/home" class="home">
            <div>
              <img src="../assets/home_img.png" alt="img">
            </div>
            <div>
              <h2>Domov</h2>
            </div>
          </router-link>
          <router-link to="/classes" class="navigation_link">
            <div>
              <img src="../assets/books_img.png" alt="img">
            </div>
            <div>
              <h2>Predmeti</h2>
            </div>
          </router-link>
        </div>
        <div class="teacher" v-if="type == 'teacher'">
          <router-link to="/home" class="home">
            <div>
              <img src="../assets/home_img.png" alt="img">
            </div>
            <div>
              <h2>Domov</h2>
            </div>
          </router-link>
          <router-link to="/classes" class="navigation_link">
            <div>
              <img src="../assets/books_img.png" alt="img">
            </div>
            <div>
              <h2>Predmeti</h2>
            </div>
          </router-link>
        </div>
        <div class="admin" v-if="type == 'admin'">
          <router-link to="/home" class="home">
            <div>
              <img src="../assets/home_img.png" alt="img">
            </div>
            <div>
              <h2>Domov</h2>
            </div>
          </router-link>
          <router-link to="/classes" class="navigation_link">
            <div>
              <img src="../assets/books_img.png" alt="img">
            </div>
            <div>
              <h2>Predmeti</h2>
            </div>
          </router-link>
          <div class="link_space">
            <router-link to="/students" class="navigation_link">
              <div>
                <img src="../assets/student_img.png" alt="img">
              </div>
              <div>
                <h2>Dijaki</h2>
              </div>
            </router-link>
            <router-link to="/teachers" class="navigation_link">
              <div>
                <img src="../assets/teacher_img.png" alt="img">
              </div>
              <div>
                <h2>Profesorji</h2>
              </div>
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <div class="user">
      <router-link to="/profile">
        <div class="user-info">
          <img :src="image" alt="user">
          <p>{{ name }} {{ surname }}</p>
          <p v-if="type == 'student'">Razred: {{ razred }}</p>
        </div>
      </router-link>
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
      image: "",
      school_year: "",
      type: "",
    }
  },
  methods: {
    getSchoolYear() {
      let date = new Date();
      let year = date.getFullYear();
      let month = date.getMonth();
      let nextYear = new Date(new Date().setFullYear(new Date().getFullYear() + 1)).getFullYear()

      if (month < 8) {
        this.school_year = (year - 1 + "/" + year);
      } else {
        this.school_year = (year + "/" + nextYear);
      }
    },
    getUserImage(tip: string) {
      if (tip == "student") {
        this.image = "../assets/student_profile_pic.png";
      } else if (tip == "teacher") {
        this.image = "../assets/teacher.png";
      } else if (tip == "admin") {
        this.image = "../assets/admin_profile_pic.png";
      } else {
        return this.$router.push('/');
      }
    },

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
                if (response.data.name != "") {
                  this.name = response.data.name;
                  this.surname = response.data.surname;
                  this.razred = response.data.class;
                } else if (response.data.error == "token") {
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
    logout(){
      Swal.fire({
        title: 'Potrdite odjavo',
        text: 'Se želite odjaviti?',
        icon: "question",
        confirmButtonText: 'Da, želim se odjaviti',
        confirmButtonColor:  '#4377df',
        cancelButtonText: 'Ne, ne želim se odjaviti',
        showCancelButton: true,
        cancelButtonColor: "#e63946"
      })
          .then((event) => {
            if(event.isConfirmed){
              sessionStorage.clear();
              localStorage.clear();
              this.$router.push('/');
            }
            else {
              Swal.close()
            }
          })
    }

  },
  created() {
    this.getSchoolYear();
    const type = sessionStorage.getItem('type');
    if (type != null) {
      this.getUserImage(type);
      this.getUser(type);
    }

  }

}
</script>

<style scoped>

.navbar {
  width: 15vw;
  height: 100vh;
  background: linear-gradient(185deg, rgba(24, 37, 84, 1) 0%, rgba(40, 100, 180, 1) 40%, rgba(117, 171, 228, 1) 100%);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
}

.solsko-leto {
  width: 100%;
  align-items: center;
  justify-content: center;
  text-align: center;
  color: white;
}

.logo {
  padding-bottom: 10vh;
  padding-top: 1vh;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.logo img {
  height: 15vh;
}

.user img {
  height: 10vh;
}

.home img {
  height: 6vh;
  padding-right: 10px;
}

.logout img {
  height: 5vh;
  padding-right: 10px;
}

.user {
  margin-bottom: 5vh;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: white;
  gap: 2vh;
}

.menu {
  width: 100%;
}

.options {
  display: flex;
  flex-direction: column;
  width: 100%;
  color: white;
  font-size: large;
  justify-content: space-between;
  padding-left: 1vw;
  padding-bottom: 20vh;
}

.navigation_link {
  display: flex;
  flex-direction: row;
  height: 8vh;
  align-items: center;
  justify-content: left;
}

.navigation_link img {
  height: 6vh;
}

.link_space {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding-left: 8px;
}

.home {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: left;
  font-size: large;
  padding-left: 5px;
}

.logout{
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-start;
  font-size: large;
  padding-left: 1vw;
  margin-right: auto;
  padding-bottom: 2vh;
  cursor: pointer;
}

</style>