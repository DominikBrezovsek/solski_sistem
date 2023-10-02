<template>
    <div class="navbar">
        <div class="logo">
          <div>
            <img src="https://smv.usdd.company/API/public/images/logo.png" alt="logo">
          </div>
        </div>
        <div class="user">
            <img :src="image" alt="user">
            <div class="user-info">
                <p>{{name}} {{surname}}</p>
                <p>Razred: {{class}}</p>
                <p>Šolsko leto {{school_year}}</p>
            </div>
        </div>
        <div class="menu">
          <li>A</li>
          <li>A</li>
          <li>A</li>
        </div>
    </div>
</template>

<script lang="ts">
import axios from 'axios';



export default  {
    data() {
        return {
            name: "",
            surname: "",
            class: "",
            image: "",
            school_year: "",
        }
    },
    methods: {
        getSchoolYear() {
            let date = new Date();
            let year = date.getFullYear();
            let month = date.getMonth();
            if (month < 8) {
                this.school_year = (year - 1 + "/" + year);
            }
            else {
                this.school_year = (year + "/" + year + 1);
            }
        },
        getUserImage(tip:string){
            if(tip == "student"){
                return "https://smv.usdd.company/API/public/images/studenti.png/";
            }
            else if(tip == "teacher"){
                return "https://smv.usdd.company/API/public/images/ucitelji.png/";
            }
            else{
                return "https://smv.usdd.company/API/public/images/default.png";
            }
        },

        getUser(tip:string){
            const user = new FormData();;
            switch (tip){
                case "student":{
                    axios.get('https://smv.usdd.company/API/public/api/student/get')
                    .then((response) => {
                        this.name = response.data[0].name;
                        this.surname = response.data[0].surname;
                        this.class = response.data[1].class;
                    }, (error) => {
                        console.log(error);
                    });
                    break
                }
                case "teacher":{
                    axios.get('https://smv.usdd.company/API/public/api/teacher/get',)
                    .then((response) => {
                        this.name = response.data.name;
                        this.surname = response.data.surname;
                    }, (error) => {
                        console.log(error);
                    });
                    break
                }
                case "admin": {
                    axios.get('https://smv.usdd.company/API/public/api/admin/get')
                    .then((response) => {
                        this.name = response.data.name;
                        this.surname = response.data.surname;
                    }, (error) => {
                        console.log(error);
                    });
                    break
                }


            }
        }
        
    },
    created() {
        this.getSchoolYear();
        const type = sessionStorage.getItem('type');
        if ( type != null ) {
            this.image = this.getUserImage(type);
            this.getUser(type);
        }

    }
    
}
</script>

<style scoped>

.navbar {
  width: 20vw;
  height: 100vh;
  background: linear-gradient(185deg, rgba(24, 37, 84, 1) 0%, rgba(40, 100, 180, 1) 40%, rgba(117, 171, 228, 1) 100%);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-evenly;
}

.logo{
  margin-bottom: 20vh;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo img{
  height: 15vh;
}

.user img{
  height: 10vh;
}

.user{
  margin-bottom: 25vh;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: white;
  gap: 2vh;
}

.menu {
  margin-bottom: 10vh;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  flex-direction: column;
}

</style>