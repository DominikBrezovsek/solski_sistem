<template>
  <div class="flex flex-row">
    <Sidebar/>
    <div class="content">
      <RecentSubjects v-if="user_type == 'student'"/>
      <RecentAssignments v-if="user_type == 'student'"/>
      <GivenAssignments v-if="user_type == 'teacher'"/>
    </div>
  </div>

</template>

<script lang="ts">
import Sidebar from '@/components/Sidebar.vue';
import RecentSubjects from "@/components/RecentSubjects.vue";
import RecentAssignments from "@/components/RecentAssignments.vue";
import GivenAssignments from "@/components/RecentAssignments.vue";

export default {
  components: {
    Sidebar,
    RecentSubjects,
    RecentAssignments,
    GivenAssignments
  },
  data() {
    return {
      user_type: ""
    }
  },
  created() {
    const userType = sessionStorage.getItem('type');
    if (userType != null) {
      this.user_type = userType;
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
}
</style>