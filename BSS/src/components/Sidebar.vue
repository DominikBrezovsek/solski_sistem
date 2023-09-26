<template>
    <div class="navbar">
        <div class="logo">
            <img src="" alt="logo">
        </div>
        <div class="user">
            <img :src="image" alt="user">
            <div class="user-info">
                <p>{{name}} {{surname}}</p>
                <p>{{school_year}}</p>
            </div>
        </div>
        <div class="menu">
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

        getUser(tip:string, id:number){
            const user = new FormData();
            user.append('id', id.toString());
            switch (tip){
                case "student":{
                    axios.post('https://smv.usdd.company/API/public/api/student/get', user)
                    .then((response) => {
                        this.name = response.data.ime;
                        this.surname = response.data.priimek;
                        this.class = response.data.razred;
                    }, (error) => {
                        console.log(error);
                    });
                    break
                }
                case "teacher":{
                    axios.post('https://smv.usdd.company/API/public/api/teacher/get', user)
                    .then((response) => {
                        this.name = response.data.ime;
                        this.surname = response.data.priimek;
                    }, (error) => {
                        console.log(error);
                    });
                    break
                }
                case "admin": {
                    axios.post('https://smv.usdd.company/API/public/api/admin/get', user)
                    .then((response) => {
                        this.name = response.data.ime;
                        this.surname = response.data.priimek;
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
        const type = localStorage.getItem('type');
        if ( type != null) {
            this.image = this.getUserImage(type);
            this.getUser(type, parseInt(localStorage.getItem('user')!));
        }

    }
    
}
</script>