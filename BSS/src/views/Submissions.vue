<template>
  <div class="flex flex-row">
    <Sidebar/>
    <div class="submitted-assignments">
      <div class="tittle">
        <h1>Oddane naloge</h1>
      </div>
      <div class="assignment">
        <div class="submitted-assignment" v-for="s in submissions">
          <div class="assignment-inner-div">
            <p>{{ s.name }}</p>
            <p>{{ s.surname }}</p>
            <p>{{ s.tittle }}</p>
            <p>{{ s.handedInAt }}</p>
          </div>
          <div class="button">
            <p><img src="../assets/file.png" alt="Prenesi oddajo" @click="getFile(s.id)"></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">

import axios from 'axios'
import Swal from "sweetalert2";
import Sidebar from "@/components/Sidebar.vue";

export default {
  components: {Sidebar},
  data() {
    return {
      submissions: Array()
    }
  },
  methods: {
    getSubAssignment() {
      let token = sessionStorage.getItem('token');
      const subjectId = sessionStorage.getItem('subjectId')
      if (token != null && subjectId != null) {
        const jwt = new FormData();
        jwt.append('token', token);
        jwt.append('subjectId', subjectId)
        axios.post('https://smv.usdd.company/API/public/api/assignment/submissions', jwt)
            .then((response) => {
              if (response.data.submissions != null) {
                for (let i = 0; i < (response.data.submissions).length; i++) {
                  this.submissions.push(response.data.submissions[i]);
                }
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
            })
      }
    },
    getFile(id: string) {
      let token = sessionStorage.getItem('token');
      if (token != null && id != null) {
        const jwt = new FormData();
        jwt.append('token', token);
        jwt.append('submissionId', id)
        axios.post('https://smv.usdd.company/API/public/api/assignment/subFile', jwt, {responseType: "arraybuffer"})
            .then((response) =>{
              let fileName = response.headers['content-disposition'].split(';')[1].split('filename=')[1].split('.')[0]
              if(fileName.search('"') != -1){
                fileName = response.headers['content-disposition'].split(';')[1].split('filename=')[1].split('.')[0].split('"')[1].split('"')[0]
              }
              let blob = new Blob([response.data], {type: response.headers['content-type']})
              let link = document.createElement('a')
              link.href = window.URL.createObjectURL(blob)
              link.download = fileName
              link.click()
        })
      }
    }
  },
  created() {
    this.getSubAssignment()
  },
}

</script>


<style scoped>
.submitted-assignments {
  height: 80vh;
  width: 82vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-left: 2vw;
  counter-reset: section;
}

.submitted-assignment {
  height: 2vh;
  width: 40vw;
  display: flex;
  flex-direction: row;
  justify-content: left;
  align-items: center;
  gap: 5vw;
  margin-bottom: 5vh;
  margin-left: 2vw;
  border-bottom: 4px solid #2e5baa;
  padding-bottom: 2vh;
  padding-top: 1vh;
}

.assignment-inner-div {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: left;
  gap: 5vw;

}

.submitted-assignment:hover {
  border-bottom: 4px solid #5891d3;
  transition: 0.2s ease-in-out;
  cursor: pointer;
}


.submitted-assignment img {
  width: 2vw;
}

.submitted-assignment img:hover,
.submitted-assignment img:focus {
  width: 2.2vw;
}

.submitted-assignment h2, p {
  padding-top: 1vh;
  padding-bottom: 3vh;
}

.submitted-assignment h2::before {
  counter-increment: section;
  content: counter(section) ": ";
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

.assignment {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
  width: 100%;
  height: 100%;
  padding-top: 5vh;
  background: #dee8fb;
  border-top-left-radius: 20px;
  border-top-right-radius: 20px;
  box-shadow: -1px -1px 13px 1px #00000040
}
</style>