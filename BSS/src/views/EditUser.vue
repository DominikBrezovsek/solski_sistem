<template>
  <div class="flex flex-row">
    <Sidebar/>
    <EditTeacher v-if="user == 'teacher'" />
    <EditStudent v-if="user == 'student'"/>
  </div>

</template>

<script setup lang="ts">
import EditTeacher from "@/components/EditTeacher.vue";
import Sidebar from "@/components/Sidebar.vue";
import EditStudent from "@/components/EditStudent.vue";
const user = sessionStorage.getItem('currentType')
</script>

<style scoped>

</style>