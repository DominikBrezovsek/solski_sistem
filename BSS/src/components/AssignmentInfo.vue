<template>
  <div class="assignments-info">
    <div class="tittle">
      <h1>{{ subject }}</h1>
    </div>
    <div class="tittle_assignment">
      <h2>{{ tittle }}</h2>
    </div>
    <p>{{ description }}</p>
    <p @click="getFile" class="file-download">Datoteka: {{fileName}}</p>
    <p>Dodelelil/a: {{ name }} {{ surname }}</p>
  </div>
</template>

<script lang="ts">
import axios from "axios";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      subject: '',
      tittle: '',
      description: '',
      name: '',
      surname: '',
      file: null,
      fileName: '',
    }
  },
  methods: {
    getAssignment() {
      const token = sessionStorage.getItem('token');
      let assId = sessionStorage.getItem('assignmentId');

      if (token != null) {
        if (assId) {
          const data = new FormData();
          data.append('token', token);
          data.append('assignmentId', assId);
          axios.post('https://smv.usdd.company/API/public/api/assignment/get', data)
              .then((response) => {
                if (response.data.assignment != null) {
                  this.subject = response.data.assignment.subject;
                  this.tittle = response.data.assignment.tittle;
                  this.description = response.data.assignment.description;
                  this.name = response.data.assignment.name;
                  this.surname = response.data.assignment.surname
                  sessionStorage.setItem('subjectId', response.data.assignment.subjectId)

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
      }
    },
    getFile() {
      const token = sessionStorage.getItem('token');
      let assId = sessionStorage.getItem('assignmentId');
      if (token != null) {
        if (assId) {
          const data = new FormData();
          data.append('token', token);
          data.append('assignmentId', assId);
          axios.post('https://smv.usdd.company/API/public/api/assignment/file', data, {responseType: "arraybuffer"})
              .then((response) => {
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
    getFileName(){
      const token = sessionStorage.getItem('token');
      let assId = sessionStorage.getItem('assignmentId');
      if (token != null) {
        if (assId) {
          const data = new FormData();
          data.append('token', token);
          data.append('assignmentId', assId);
          axios.post('https://smv.usdd.company/API/public/api/assignment/file', data, {responseType: "arraybuffer"})
              .then((response) => {
                let file = response.headers['content-disposition'].split(';')[1].split('filename=')[1].split('.')[0]
                if(file.search('"') != -1){
                  this.fileName = response.headers['content-disposition'].split(';')[1].split('filename=')[1].split('.')[0].split('"')[1].split('"')[0]
                }
                else {
                  this.fileName = response.headers['content-disposition'].split(';')[1].split('filename=')[1].split('.')[0]
                }

              })
        }
      }
    }
  },
  created() {
    this.getAssignment();
    this.getFileName();
  },
}

</script>

<style scoped>
.assignments-info {
  display: flex;
  flex-direction: column;
  gap: 2vh;
  width: 85%;
  height: 45vh;
  justify-content: flex-start;
  align-items: flex-start;
  padding: 3vh;
}

.assignments-info h2, p {
  padding: 2vh;
}

.assignments-info h2 {
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

.tittle_assignment {
  width: 100%;
  display: flex;
  justify-content: center;
  color: black;
  font-size: x-large;
  flex-direction: column;
  overflow: hidden;
}
.file-download{
  cursor: pointer;
}
</style>