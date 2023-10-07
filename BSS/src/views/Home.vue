<template>
  <div class="flex flex-row">
    <Sidebar />
    <RecentSubjects v-if="user_type == 'student'" />
  </div>

</template>


<script lang="ts" >
import Sidebar from '@/components/Sidebar.vue';
import RecentSubjects from "@/components/RecentSubjects.vue";
export default {
  components:{
    Sidebar,
    RecentSubjects
  },
  data (){
    return {
      user_type: ""
    }
  },
  created (){
    const userType = sessionStorage.getItem('type');
    if (userType != null){
      this.user_type = userType;
    }
  }
}
</script>