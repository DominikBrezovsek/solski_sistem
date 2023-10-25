<template>
  <div class="home">
    <div class="tittle">
      <h1>BSS</h1>
    </div>
    <div>
      <p class="p">is a modern digital platform dedicated to enchancing the educational journey of students and
        teachers
        alike.</p>
    </div>
  </div>
</template>

<script setup lang="ts">

</script>
<style scoped>
.home {
  display: flex;
  flex-direction: column;
  align-content: center;
  justify-content: center;
  width: 83vw;
  height: 100%;
  padding: 15vw;
}
.tittle {
  width: 100%;
  display: flex;
  justify-content: center;
  color: grey;
  font-size: xxx-large;
  flex-direction: column;
  overflow: hidden;
  padding: 2vw;
}
.tittle h1{
  justify-content: center;
  text-align: center;
}
.p {
  color: grey;
  padding: 2vw;
  width: 100%;
  text-align: center;
}
</style>