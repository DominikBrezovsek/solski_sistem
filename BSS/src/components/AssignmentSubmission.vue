<template>
  <div class="AssignmentsSubmission">
    <div class="tittle">
      <h1>Oddaja naloge</h1>
    </div>
  </div>
</template>

<script lang="ts">
export default {
  data() {
    return {
      assignment: Array()
    }
  },
  methods: {
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