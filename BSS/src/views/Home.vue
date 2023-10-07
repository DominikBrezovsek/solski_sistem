<template>
  <div class="flex flex-row">
    <Sidebar />
    <RecentSubjects/>
  </div>

</template>


<script lang="ts" setup>
import Sidebar from '@/components/Sidebar.vue';
import RecentSubjects from "@/components/RecentSubjects.vue";
</script>