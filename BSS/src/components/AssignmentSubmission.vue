<template>
  <div class="AssignmentsSubmission">
    <div class="tittle">
      <h1>Oddaja naloge</h1>
    </div>
    <div class="flex flex-col">
      <h4>Naloži datoteko</h4>
      <input type="file" @change="storeFile">
      <button @click="submitFile">Submit</button>
    </div>
  </div>
</template>

<script lang="ts">
import axios from "axios";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      assignment: Array(),
      file: null,
    }
  },
  methods: {
    storeFile(event: any){
      this.file = event.target.files[0]
    },
    submitFile(){
      const path = 'https://smv.usdd.company/API/public/api/';
      const subjectId = sessionStorage.getItem('subjectId')
      const assignmentId = sessionStorage.getItem('assignmentId');
      const file = new FormData();
      const token = sessionStorage.getItem('token')
      if(this.file && token != null && subjectId != null && assignmentId != null) {
        file.append('file', this.file)
        file.append('subjectId', subjectId)
        file.append('assignmentId', assignmentId)
        file.append('token', token)
        axios.post(path + 'assignment/submit', file)
            .then((response) =>{
              if (response.data.error == "duplicate") {
                Swal.fire({
                  title: 'Ponovna oddaja naloge?',
                  text: 'Želite ponovno oddati nalogo? Stara datoteka bo odstranjena iz strežnika.',
                  icon: 'question',
                  confirmButtonText: 'Da, ponovno oddaj',
                  confirmButtonColor: "#4377df",
                  cancelButtonText: 'Ne, ne želim ponovne oddaje',
                  showCancelButton: true,
                  cancelButtonColor: "#e63946"
                }).then((isConfirm) =>{
                  if (isConfirm.isConfirmed){
                    file.append('resubmit', 'true')
                    axios.post(path + 'assignment/submit', file)
                        .then ((response => {
                          if (response.data[0].resubmitted == "true"){
                            Swal.fire({
                              title: 'Oddaja uspešna',
                              text: 'Uspešno ste ponovno oddali nalogo.',
                              icon: 'success',
                              confirmButtonText: 'Odlično!',
                              confirmButtonColor: "#4377df"
                            }) .then((event) => {
                              if (event.isConfirmed){
                                this.$router.push
                              }
                            });
                          }
                        }))
                  } else {
                    Swal.close();
                  }
                })
              } else if (!response.data.error){
                Swal.fire({
                  title: 'Oddaja uspešna',
                  text: 'Uspešno ste oddali nalogo.',
                  icon: "success",
                  confirmButtonText: 'Juppii!',
                  confirmButtonColor: '#4377df'
                })
                    .then((event) => {
                      if (event.isConfirmed){
                        this.$router.push('/classes')
                      }
                    })
              }
            });
      }

    }
  },
  created() {
  }
}
</script>

<style scoped>
.AssignmentsSubmission {
  display: flex;
  flex-direction: column;
  gap: 2vh;
  width: 85%;
  height: 45vh;
  justify-content: flex-start;
  align-items: flex-start;
  padding: 3vh;
}
.tittle{
  width: 100%;
  margin-top: 2vh;
  margin-bottom: 1vh;
  margin-left: 5vh;
  display: flex;
  justify-content: center;
  color: grey;
  font-size: xx-large;
  flex-direction: column;
  overflow: hidden;
}
</style>