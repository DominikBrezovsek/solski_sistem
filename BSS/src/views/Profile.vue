<template>
  <div class="flex flex-row">
    <Sidebar/>
    <div class="content">
      <ProfileInfo/>
    </div>
  </div>
</template>

<script setup lang="ts">
import Sidebar from "@/components/Sidebar.vue";
import ProfileInfo from "@/components/ProfileInfo.vue";
</script>

<style scoped>

</style>