<template>
<div class="assignments-info" v-for="a in assignment">
  <h2>Naslov: {{a.tittle}}</h2>
  <p>Opis naloge: {{a.description}}</p>
  <p>Dodelelil/a: {{a.name}} {{a.surname}}</p>
</div>
</template>

<script  lang="ts">
import axios from "axios";
export default {
  data() {
    return{
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
                }
              }else {
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
  width: 90%;
  height: 45vh;
  justify-content: flex-start;
  align-items: flex-start;
  padding: 5vh;
  border: 2px solid #2e5baa;
  border-radius: 20px;
  margin-top: 2vh;
}

.assignments-info h2, p {
  padding: 2vh;
}
</style>