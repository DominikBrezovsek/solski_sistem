<template>
  <div class="flex flex-row">
    <Sidebar/>
    <div class="content">
      <RecentSubjects v-if="user_type == 'student'"/>
      <RecentAssignments v-if="user_type == 'student'"/>
      <h1 v-if="user_type == 'teacher'" class="tittle">Zadnje oddaje</h1>
      <Submissions v-if="user_type == 'teacher'"/>
    </div>
  </div>

</template>

<script lang="ts">
import Sidebar from '@/components/Sidebar.vue';
import RecentSubjects from "@/components/RecentSubjects.vue";
import RecentAssignments from "@/components/RecentAssignments.vue";
import GivenAssignments from "@/components/RecentAssignments.vue";
import Submissions from "@/components/Submissions.vue";

export default {
  components: {
    Sidebar,
    RecentSubjects,
    RecentAssignments,
    GivenAssignments,
    Submissions
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
}
</style>