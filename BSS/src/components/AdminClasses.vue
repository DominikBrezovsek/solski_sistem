<template>
  <div class="Admin_classes">
    <div class="tittle">
      <h1>Predmeti</h1>
    </div>
    <div class="classes">
      <div class="search-bar">
        <div>
          <h1>Iskanje</h1>
        </div>
      </div>
      <div class="table">
        <div class="class-table">
          <p>Predmet1</p>
          <p>Predmet2</p>
          <p>Predmet3</p>
          <p>Predmet4</p>
          <p>Predmet5</p>
          <p>Predmet6</p>
          <p>Predmet7</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

</script>

<style scoped>
.Admin_classes {
  height: 10vh;
  width: 82vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  padding-left: 2vw;
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
}
.table {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
  width: 82vw;
  height: 80vh;
  padding-top: 5vh;
  background: #dee8fb;
  border-top-left-radius: 20px;
  border-top-right-radius: 20px;
  box-shadow: -1px -1px 13px 1px #00000040
}

.class-table {
  height: 2vh;
  width: 40vw;
  display: flex;
  flex-direction: column;
  justify-content: left;
  align-items: center;
  margin-bottom: 5vh;
  margin-left: 2vw;
  border-bottom: 4px solid #2e5baa;
  padding-bottom: 2vh;
  padding-top: 1vh;
}

.class-table:hover {
  border-bottom: 4px solid #5891d3;
  transition: 0.2s ease-in-out;
  cursor: pointer;
}

.class-table h2 {
  color: black;
  font-size: large;
  width: 15vw;
  line-break: auto;
}

.class-table h2, p {
  padding-top: 1vh;
  padding-bottom: 3vh;
}

.class-table h2::before {
  counter-increment: section;
  content: counter(section) ": ";
}
</style>