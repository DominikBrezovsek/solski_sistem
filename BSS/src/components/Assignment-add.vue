<template>
  <div class="AddAssignment">
    <div class="tittle">
      <h1>Dodaj nalogo</h1>
    </div>
    <div class="flex flex-col">
      <div class="button">
        <label class="button-add">
          Dodaj
        </label>
      </div>

    </div>
  </div>
</template>

<script lang="ts" setup>
import axios from "axios";
import Swal from "sweetalert2";

</script>

<style scoped>
.AddAssignment {
  display: flex;
  flex-direction: column;
  gap: 2vh;
  width: 100%;
  height: 30vh;
  justify-content: flex-start;
  align-items: flex-start;
  padding-left: 2vw;
}

.AddAssignment h2, p {
  padding: 2vh;
}

.AddAssignment h2 {
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
.button-add {
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
  width: 20vw;
}

.button-add:after {
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

.button-add:hover:after {
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
.button{
  display: flex;
  flex-direction: column;
  gap: 2vh;
  width: 100%;
  padding: 2vh;
}
</style>