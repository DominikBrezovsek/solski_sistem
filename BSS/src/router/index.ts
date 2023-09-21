import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '../views/LoginPage.vue'
import RegisterPage1 from '../views/RegisterPageSt1.vue'
import RegisterPage2 from '../views/RegisterPageSt2.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'Login',
      component: LoginPage
    },
    {
      path: '/register_st1',
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
