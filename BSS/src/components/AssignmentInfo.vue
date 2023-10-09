<template>
  <div class="assignments-info" v-for="a in assignment">
    <div class="tittle">
      <h1>{{ a.subject }}</h1>
    </div>
    <div class="tittle_assignment">
      <h2>{{ a.tittle }}</h2>
    </div>
    <p>{{ a.description }}</p>
    <p>Dodelelil/a: {{ a.name }} {{ a.surname }}</p>
  </div>
</template>

<script lang="ts">
import axios from "axios";

export default {
  data() {
    return {
      assignment: Array()
    }
  },
  methods: {
    getAssignment() {
      const assignment = Array();
      const token = sessionStorage.getItem('token');
      const assId = sessionStorage.getItem('assignmentId');

      if (token != null && assId != null) {
        const data = new FormData();
        data.append('token', token);
        data.append('assignmentId', assId);
        axios.post('https://smv.usdd.company/API/public/api/assignment/get', data)
            .then((response) => {
              const assResponse = response.data.assignment;
              if (assResponse != null) {
                for (let i = 0; i < assResponse.length; i++) {
                  this.assignment.push(assResponse[i]);
                  console.log(this.assignment);
                  sessionStorage.setItem('subId', assResponse[i].subjectId);
                }
              } else {
                this.assignment.push({'tittle': 'Naloga ni bila najdena'})
              }
            })
      }
    }
  },
  created() {
    this.getAssignment()
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

</style>