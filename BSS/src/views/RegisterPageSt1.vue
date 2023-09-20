<template>
    <div class="login flex flex-row justify-between">
        <div class="about-side w-4/12 flex justify-center align-center">
            <div class="mt-auto mb-auto about-inner">
                <div class="tittle-about text-5xl font-bold">
                    <h1>Welcome to</h1>
                </div>
                <div class="image-about ml-auto mr-auto">
                    <img src="../assets/logo_BSS.png" alt="BSS logo" />
                </div>
                <div class="text-3xl font-bold ml-auto mr-auto">
                    <h1>BSS E-portal</h1>
                </div>
                <div class="text-xl font-normal ml-auto mr-auto text-about">
                    <p>BSS is a modern digital platform dedicated to enchancing the educational journey of students and
                        teachers
                        alike.</p>
                </div>
            </div>
        </div>
        <div class="login-side w-8/12 flex flex-col justify-center">
            <div class="login-tittle flex flex-col">
                <div class="tittle ml-auto mr-auto text-5xl font-bold text-black">
                    <h1>Register</h1>
                </div>
                <div class="subtittle text-xl font-medium ml-auto mr-auto">
                    Create your student account.
                </div>
            </div>
            <div class="flex flex-col login-form w-full">
                <div class="flex flex-col">
                    <label for="name" class="label">Name</label>
                    <input id="name" class="username" type="text" v-model="name" placeholder="John" />
                </div>
                <div class="flex flex-col">
                    <label for="password" class="label">Surname</label>
                    <input id="password" class="password" type="text" v-model="surname" placeholder="Doe" />
                </div>
                <div class="flex flex-col">
                    <label for="school" class="label">School</label>
                    <select name="school" id="school" v-model="currentSchool" @change="getClasses" >
                        <option value="" disabled selected>Select your school</option>
                        <option v-for="school in schools" :value="school.naziv">{{ school.naziv }}</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label for="class" class="label">Class</label>
                    <select name="class" id="class">
                        <option v-for="razred in classes" :value="razred.naziv">{{ razred.naziv }}</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label for="email" class="label">Email</label>
                    <input id="email" class="username" type="email" v-model="email" placeholder="johndoe@school.domain"/>
                </div>
                <div>
                    <button @click="nextStep" class="login-button">Next step</button>
                </div>
            </div>
            <div class="no-account">
                <p>Already have an account?<RouterLink to="/login">Login</RouterLink>
                </p>
            </div>
        </div>
    </div>
</template>

<script  lang="ts">
import { RouterLink, RouterView } from 'vue-router';
import axios from 'axios';

export default {
    data() {
        return {
            name: "",
            surname: "",
            email: "",
            school: "",
            class: "",
            schools: [],
            classes: [],
            currentSchool: ""
        }
    },
    created() {
        axios.get('https://smv.usdd.company/API/public/api/schools/get')
            .then((response) => {
                this.schools = response.data;
            }, (error) => {
                console.log(error);
            });
    },
    methods: {
        register() {
            const credentials = new FormData();
            credentials.append('name', this.name);
            credentials.append('surname', this.surname);
            credentials.append('email', this.email);
            credentials.append('school', this.school);
            credentials.append('class', this.class);
            axios.post('https://smv.usdd.company/API/public/api/student/create', credentials)
                .then((response) => {
                    console.log(response.data.logged);
                    if (response.data.created == "success") {
                        this.$router.push('/login');
                    } else if (response.data.error == "duplicate") {
                        alert("User already exists");
                    } else {
                        alert("User creation failed");
                    }
                }, (error) => {
                    console.log(error);
                });
        },
        getClasses() {
            const school = new FormData();
            console.log(this.currentSchool);
            school.append('school', this.currentSchool);
            axios.post('https://smv.usdd.company/API/public/api/class/get', school)
                .then((response) => {
                    this.classes = response.data;
                }, (error) => {
                    console.log(error);
                });
        }
    }
}
</script>

<style scoped>
* {
    margin: 0;
    padding: 0;

}

.login {
    width: 100vw;
    height: 100vh;
    left: 0;
}

.login-tittle {
    gap: 2vh;
    margin-top: 5vh;

}

.subtittle {
    text-align: center;
    margin-left: auto;
    margin-right: auto;
    width: 90%;
}

.login-form{
    margin-left: auto;    
    margin-right: auto;
    width: 50vw;
    justify-items: center;
    align-items: center;
    margin-top: auto;
    margin-bottom: auto;

}
.login-side{
    justify-content: center;
    align-content: center;
    
}


.username,
.password {
    width: 35vw;
    height: 5vh;
    border: 1px solid black;
    border-radius: 5px;
    margin-bottom: 2vh;
}

.password::placeholder,
.username::placeholder {
    font-size: 1.5vh;
    padding-left: 1vw;
}

select {
    width: 35vw;
    height: 5vh;
    border: 1px solid black;
    border-radius: 5px;
    margin-bottom: 2vh;
    font-size: 1.5vh;
    padding-left: 1vw;
}

.login-button {
    width: 25vw;
    height: 5vh;
    border: none;
    border-radius: 10px;
    margin-top: 2vh;
    background-color: #4377df;
    color: white;
    font-size: 2vh;
}

.login-button:hover {
    background-color: #2e5baa;
}

.no-account {
    font-size: 2vh;
    margin-left: 10vw;
    margin-bottom: auto;
    margin-top: -15vh;
}

.no-account a {
    color: #4377df;
}

.no-account a:hover {
    color: #2e5baa;
}

.label {
    font-size: 2vh;
    margin-bottom: 0.5vh;
    margin-right: auto;
}

.about-side {
    width: 100vh;
    background: rgb(24, 37, 84);
    background: linear-gradient(185deg, rgba(24, 37, 84, 1) 0%, rgba(40, 100, 180, 1) 40%, rgba(117, 171, 228, 1) 100%);
}

.about-inner {
    width: 80%;
    height: 80vh;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.tittle-about {
    margin-left: auto;
    margin-right: auto;
}



.image-about {
    width: 50%;

}

.text-about {
    width: 80%;
    text-align: center;
}
</style>

