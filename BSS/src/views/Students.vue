<template>
  <div class="flex flex-row">
    <Sidebar/>
    <AdminStudents/>
  </div>

</template>

<script lang="ts" setup>
import Sidebar from '@/components/Sidebar.vue';
import AdminStudents from "@/components/AdminStudents.vue";
</script>