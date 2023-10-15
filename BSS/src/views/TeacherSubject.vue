<template>
  <div class="flex flex-row">
    <Sidebar/>
    <div class="content">
      <SubjectInfo/>
      <GivenAssignments/>
      <AddAssignment/>
    </div>
  </div>
</template>

<script lang="ts">
import Sidebar from "@/components/Sidebar.vue";
import SubjectInfo from "@/components/SubjectInfo.vue";
import GivenAssignments from "@/components/GivenAssignments.vue";
import AddAssignment from "@/components/Assignment-add.vue";
export default {
  components:{
    Sidebar,
    SubjectInfo,
    GivenAssignments,
    AddAssignment
  },
  methods: {
    checkUser() {
      const user = sessionStorage.getItem('type');
      if (user != null && user != 'teacher') {
        this.$router.push('/home')
      }
    }
  },
  created() {
    this.checkUser()
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