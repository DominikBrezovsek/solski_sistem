<template>
  <div class="flex flex-row">
    <Sidebar/>
    <div class="content">
      <RecentSubjects v-if="user_type == 'student'"/>
      <RecentAssignments/>
    </div>
  </div>

</template>

<script lang="ts">
import Sidebar from '@/components/Sidebar.vue';
import RecentSubjects from "@/components/RecentSubjects.vue";
import RecentAssignments from "@/components/RecentAssignments.vue";

export default {
  components: {
    Sidebar,
    RecentSubjects,
    RecentAssignments
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