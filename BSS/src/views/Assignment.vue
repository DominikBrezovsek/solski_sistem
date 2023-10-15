<template>
  <div class="flex flex-row">
    <Sidebar/>
    <div class="content">
      <AssignmentInfo/>
      <AssignmentSubmission v-if="user_type == 'student'"/>
    </div>
  </div>
</template>

<script lang="ts">
import Sidebar from "@/components/Sidebar.vue";
import AssignmentInfo from "@/components/AssignmentInfo.vue";
import AssignmentSubmission from "@/components/AssignmentSubmission.vue";

export default {
  components: {
    Sidebar,
    AssignmentInfo,
    AssignmentSubmission
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
  width: 85%;
}
</style>