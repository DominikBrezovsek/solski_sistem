<template>
  <div class="ProfileInfo">
    <div class="tittle">
      <h1>Uporabniške informacije</h1>
    </div>
    <div class="sdfsdf">
      <p>{{ name }} {{ surname }}</p>
      <p>test</p>
      <p v-if="type == 'student'">Razred: {{ razred }}</p>
      <p>TEST2</p>
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
      type: "",
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
.tittle{
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
</style>