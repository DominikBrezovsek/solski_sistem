import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '../views/LoginPage.vue'
import RegisterPage1 from '../views/Register1.vue'
import RegisterPage2 from '../views/Register2.vue'

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
      name: 'Register',
      component: RegisterPage1
    },
    {
      path: '/register_st2',
      name: 'Register2',
      component: RegisterPage2
    }
  ]
})

export default router
