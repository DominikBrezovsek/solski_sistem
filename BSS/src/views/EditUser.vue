<template>
  <div class="flex flex-row">
    <Sidebar/>
    <EditTeacher v-if="user == 'teacher'" />
  </div>

</template>

<script setup lang="ts">
import EditTeacher from "@/components/EditTeacher.vue";
import Sidebar from "@/components/Sidebar.vue";
const user = sessionStorage.getItem('currentType')
</script>

<style scoped>

</style>