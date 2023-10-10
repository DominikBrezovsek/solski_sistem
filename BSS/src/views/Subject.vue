<template>
  <div class="flex flex-row">
    <Sidebar/>
    <div class="content">
      <SubjectInfo/>
      <GivenAssignments/>
      <SubmittedAssignments/>
    </div>
  </div>
</template>

<script setup lang="ts">
import Sidebar from "@/components/Sidebar.vue";
import SubjectInfo from "@/components/SubjectInfo.vue";
import GivenAssignments from "@/components/GivenAssignments.vue";
import SubmittedAssignments from "@/components/SubmittedAssignments.vue";
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
}
.content {
  display: flex;
  flex-direction: column;
  width: 85%;
}
</style>