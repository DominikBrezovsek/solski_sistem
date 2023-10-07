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
        <div class="student" v-if="type == 'student'">
          <div class="headings">
            <h1>Učenje</h1>
          </div>
          <router-link to="/classes" class="navigation_link" >
            <div>
              <img src="../assets/books_img.png" alt="img">
            </div>
            <div>
              <h2>Predmeti</h2>
            </div>
          </router-link>
        </div>
        <div class="teacher" v-if="type == 'teacher'">
          <div class="headings">
            <h1>Učenje</h1>
          </div>
          <router-link to="/classes" class="navigation_link" >
            <div>
              <img src="../assets/books_img.png" alt="img">
            </div>
            <div>
              <h2>Predmeti</h2>
            </div>
          </router-link>
        </div>
        <div class="admin" v-if="type == 'admin'">
          <div class="headings">
            <h1>Učenje</h1>
          </div>
          <router-link to="/classes" class="navigation_link" >
            <div>
              <img src="../assets/books_img.png" alt="img">
            </div>
            <div>
              <h2>Predmeti</h2>
            </div>
          </router-link>
          <div class="headings">
            <h1>Ljudje</h1>
          </div>
          <div class="link_space">
            <router-link to="/students" class="navigation_link" >
              <div>
                <img src="../assets/student_img.png" alt="img">
              </div>
              <div>
                <h2>Dijaki</h2>
              </div>
            </router-link>
            <router-link to="/teachers" class="navigation_link" >
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
      <div class="user-info">
        <img :src="image" alt="user">
        <p>{{ name }} {{ surname }}</p>
        <p v-if="type == 'student'">Razred: {{ class }}</p>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import axios from 'axios';
import router from "@/router";



export default {
  data() {
    return {
      name: "",
      surname: "",
      class: "",
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
      } else if(tip == "admin"){
        this.image = "../assets/admin_profile_pic.png";
      } else{
        return this.$router.push ('/');
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
                  this.class = response.data.class;
                } else if (response.data.error == "token") {
                  sessionStorage.clear();
                  this.$router.push('/');
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
  width: 20vw;
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
  margin-bottom: 10vh;
  margin-top: 1vh;
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
  margin-bottom: 15vh;
  width: 100%;
}

.headings {
  width: 90%;
  margin-bottom: 1vh;
  margin-left: 2vh;
  display: flex;
  justify-content: left;
  color: grey;
  font-size: xx-large;
  flex-direction: column;
  overflow: hidden;
}

.options{
  display: flex;
  flex-direction: column;
  width: 100%;
  color: white;
  font-size: large;
  justify-content: space-between;
}

.navigation_link {
  display: flex;
  flex-direction: row;
  height: 8vh;
  align-items: center;
  justify-content: left;
  gap: 30px;
}

.navigation_link img {
  height: 6vh;
}

.link_space{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  gap: 2vh;
}
</style>