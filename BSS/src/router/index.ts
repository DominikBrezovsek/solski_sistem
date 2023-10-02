import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '../views/LoginPage.vue'
import RegisterPage1 from '../views/Register2.vue'
import RegisterPage2 from '../views/Register1.vue'
import HomePage from '../views/Home.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'Login',
      component: LoginPage
    },
    {
      path: '/register',
      name: 'Register1',
      component: RegisterPage1
    },
    {
      path: '/register2',
      name: 'Register2',
      component: RegisterPage2
    },
    {
      path: '/home',
      name: 'Home',
      component: HomePage
    }
  ]
})

export default router
