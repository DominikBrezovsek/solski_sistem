<template>
  <div class="flex flex-row">
    <Sidebar/>
    <div class="content">
      <StudentClasses v-if="user_type == 'student'"/>
      <StudentEnroll v-if="user_type == 'student'"/>
      <TeacherClasses v-if="user_type == 'teacher'"/>
      <AdminClasses v-if="user_type == 'admin'"/>
    </div>
  </div>
</template>

<script lang="ts">
import Sidebar from '@/components/Sidebar.vue';
import StudentClasses from "@/components/StudentClasses.vue";
import TeacherClasses from "@/components/TeacherClasses.vue";
import AdminClasses from "@/components/AdminClasses.vue";
import StudentEnroll from "@/components/StudentEnroll.vue";

export default {
  components: {
    AdminClasses,
    Sidebar,
    StudentClasses,
    TeacherClasses,
    StudentEnroll,
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