<template>
  <div class="flex flex-row">
    <Sidebar/>
    <div class="content">
      <SubjectInfo/>
      <div class="button">
        <div class="button-add" @click="toSub">
          Poglej oddane naloge
        </div>
      </div>
      <TeacherAssignment/>
      <div class="button">
      <div class="button-add" @click="toAss">
        Dodaj
      </div>
    </div>
    </div>
  </div>
</template>

<script  lang="ts">
import Sidebar from "@/components/Sidebar.vue";
import SubjectInfo from "@/components/SubjectInfo.vue";
import TeacherAssignment from "@/components/TeacherAssignment.vue";
import subjectInfo from "@/components/SubjectInfo.vue";

export default {
  components: {
    SubjectInfo,
    TeacherAssignment,
    Sidebar
  },
  methods: {
    toAss() {
      this.$router.push('/assignments');
    },
    toSub() {
      this.$router.push('/submissions')
    }
  }
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
}

.content {
  display: flex;
  flex-direction: column;
  gap: 3vh;
  width: 85%;
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
  width: 40%;
  padding-top: 5vh;
  margin-left: auto;
  margin-right: auto;
}
</style>