<template>
  <div class="AssignmentsSubmission">
    <div class="tittle">
      <h1>Oddaja naloge</h1>
    </div>
    <div class="flex flex-col">
      <label class="button-file">
        <input type="file" @change="storeFile" id="file"/>
        Naloži datoteko
      </label>
      <div class="curent-file">
        <p v-if="file != null">
          {{ fileName }}
        </p>
        <p v-else-if="file == null">
          Nobena datoteka ni izbrana!
        </p>
      </div>
      <div class="button-sub">
        <button @click="submitFile">Submit</button>
      </div>

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
      fileName: ""
    }
  },
  methods: {
    storeFile(event: any) {
      this.file = event.target.files[0]
      if (this.file != null) {
        this.fileName = (event.target.files[0].name)
      }
    },
    submitFile() {
      const path = 'https://smv.usdd.company/API/public/api/';
      const subjectId = sessionStorage.getItem('subjectId')
      const assignmentId = sessionStorage.getItem('assignmentId');
      const file = new FormData();
      const token = sessionStorage.getItem('token')
      if (this.file && token != null && subjectId != null && assignmentId != null) {
        file.append('file', this.file)
        file.append('subjectId', subjectId)
        file.append('assignmentId', assignmentId)
        file.append('token', token)
        axios.post(path + 'assignment/submit', file)
            .then((response) => {
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
                }).then((isConfirm) => {
                  if (isConfirm.isConfirmed) {
                    file.append('resubmit', 'true')
                    axios.post(path + 'assignment/submit', file)
                        .then((response => {
                          console.log(response.data.resubmitted);
                          if (response.data.resubmitted == true) {
                            Swal.fire({
                              title: 'Oddaja uspešna',
                              text: 'Uspešno ste ponovno oddali nalogo.',
                              icon: 'success',
                              confirmButtonText: 'Odlično!',
                              confirmButtonColor: "#4377df"
                            }).then((event) => {
                              if (event.isConfirmed) {
                                this.$router.push
                              }
                            });
                          }
                        }))
                  } else {
                    Swal.close();
                  }
                })
              } else if (!response.data.error) {
                Swal.fire({
                  title: 'Oddaja uspešna',
                  text: 'Uspešno ste oddali nalogo.',
                  icon: "success",
                  confirmButtonText: 'Juppii!',
                  confirmButtonColor: '#4377df'
                })
                    .then((event) => {
                      if (event.isConfirmed) {
                        this.$router.push('/classes')
                      }
                    })
              }
              if (response.data.error == "token") {
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
                });
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

input[type="file"] {
  display: none;
}

.button-file {
  border: 1px solid #ccc;
  display: inline-block;
  padding: 6px 12px;
  cursor: pointer;
}

.button-sub {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 10vw;
  height: 4vh;
  border: 2px solid #315cfd;
  border-radius: 5px;
  transition: all 0.22s;
  cursor: pointer;
  font-size: 1em;
  font-weight: 550;
  text-align: center;
  color: #315cfd;
}

.button-sub:hover {
  background: #315cfd;
  color: white;
  font-size: 1.1em;
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

.flex {
  gap: 2vh;
}

.button-file {
  background-color: #315cfd;
  border: none;
  color: white;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  border-radius: 5px;
  box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.25);
  position: relative;
  overflow: hidden;
}

.button-file:after {
  content: "";
  background-color: rgba(255, 255, 255, 0.2);
  position: absolute;
  top: 50%;
  left: 50%;
  width: 5px;
  height: 5px;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  opacity: 0;
}

.button-file:hover:after {
  animation: ripple_401 1s ease-out;
}

@keyframes ripple_401 {
  0% {
    width: 5px;
    height: 5px;
    opacity: 1;
  }

  100% {
    width: 200px;
    height: 200px;
    opacity: 0;
  }
}
</style>