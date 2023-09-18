<template>
    <div class="login flex flex-row justify-between">
        <div class="about-side w-6/12 flex justify-center align-center">
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
        <div class="login-side w-5/12 flex flex-col justify-center align-center">
            <div class="login-tittle flex flex-col">
                <div class="tittle ml-auto mr-auto text-5xl font-bold text-black">
                    <h1>Login</h1>
                </div>
                <div class="subtittle text-xl font-medium ml-auto mr-auto">
                    Enter your credentials to log into your account.
                </div>
            </div>
            <div>
                <div>
                    <label for="username" class="label">Username</label>
                </div>
                <div>
                    <input id="username" type="email" v-model="username" placeholder="example@example.com" />
                </div>
                <div>
                    <label for="password" class="label">Password</label>
                </div>
                <div>
                    <input id="password" type="password" v-model="password" placeholder="Your password" />
                </div>
                <div>
                    <button @click="login" class="login-button">Login</button>
                </div>
            </div>
            <div class="no-account">
                <p>Don't have an account? <RouterLink to="/register">Register</RouterLink>
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
            username: "",
            password: ""
        }
    },
    methods: {
        login() {
            const credentials = new FormData();
            credentials.append('email', this.username);
            credentials.append('password', this.password);
            axios.post('https://smv.usdd.company/API/public/api/login/check', credentials)
                .then((response) => {
                    console.log(response.data.logged);
                    if (response.data.logged == "success") {
                        this.$router.push('/home');
                    } else {
                        alert("Invalid credentials");
                    }
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
    position: absolute;
    top: 10%;
    gap: 2vh;
    right: 8%;
}

.subtittle {
    text-align: center;
    margin-left: auto;
    margin-right: auto;
    width: 90%;
}

#username,
#password {
    width: 80%;
    height: 5vh;
    border: 1px solid black;
    border-radius: 5px;
    margin-bottom: 2vh;
}

#password::placeholder,
#username::placeholder {
    font-size: 1.5vh;
    padding-left: 1vw;
}

.login-button {
    width: 60%;
    height: 5vh;
    border: none;
    border-radius: 10px;
    margin-top: 2vh;
    background-color: #4377df;
    color: white;
    font-size: 2vh;
    margin-left: 5vw;
}

.login-button:hover {
    background-color: #2e5baa;
}

.no-account {
    margin-top: 2vh;
    font-size: 2vh;
}

.no-account a {
    color: #4377df;
}

.no-account a:hover {
    color: #2e5baa;
}

.label {
    font-size: 2vh;
    margin-bottom: 20px;
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

