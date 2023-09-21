import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '../views/LoginPage.vue'
import RegisterPage1 from '../views/Register1.vue'
import RegisterPage2 from '../views/Register2.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Login',
      component: LoginPage
    },
    {
      path: '/register',
      name: 'Register',
      component: RegisterPage1
    },
    {
      path: '/register2',
      name: 'Register',
      component: RegisterPage2
    }
  ]
})

export default router
