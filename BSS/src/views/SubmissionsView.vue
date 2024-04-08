<template>
  <div class="flex flex-row">
    <Sidebar/>
    <div class="flex flex-col">
      <div class="tittle">
        <h1>Oddane naloge</h1>
      </div>
      <Submissions/>
    </div>
  </div>
</template>

<script setup lang="ts">
import Submissions  from "@/components/Submissions.vue";
import Sidebar from "@/components/Sidebar.vue";



</script>


<style scoped>
.tittle {
  width: 100%;
  margin-top: 1vh;
  margin-bottom: 1vh;
  padding-bottom: 5vh;
  padding-left: 2vw;
  margin-left: 1vh;
  display: flex;
  justify-content: center;
  color: grey;
  font-size: xx-large;
  flex-direction: column;
}
</style>