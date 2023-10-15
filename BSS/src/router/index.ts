import {createRouter, createWebHistory} from 'vue-router'
import LoginPage from '../views/LoginPage.vue'
import RegisterPage1 from '../views/Register2.vue'
import RegisterPage2 from '../views/Register1.vue'
import ClassesPage from '../views/Classes.vue'
import StudentsPage from '../views/Students.vue'
import TeachersPage from '../views/Teachers.vue'
import HomePage from '../views/Home.vue'
import Assignment from "@/views/Assignment.vue";
import StudentSubject from "@/views/StudentSubject.vue";
import TeacherSubject from "@/views/TeacherSubject.vue";
import Profile from "../views/Profile.vue";
import EditAssignment from "@/views/EditAssignment.vue";
import AddAssignment from "@/components/AddAssignment.vue";

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
            path: '/classes',
            name: 'Classes',
            component: ClassesPage,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/students',
            name: 'Students',
            component: StudentsPage,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/teachers',
            name: 'Teachers',
            component: TeachersPage,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/home',
            name: 'Home',
            component: HomePage,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/assignments',
            name: 'Assignment',
            component: Assignment,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/profile',
            name: 'Profile',
            component: Profile,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/subject-s',
            name: 'Subject-s',
            component: StudentSubject,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/subject-t',
            name: 'Subject-t',
            component: TeacherSubject,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/assignment/edit',
            name: 'Edit assignment',
            component: EditAssignment,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/assignment/add',
            name: 'Add assignment',
            component: AddAssignment,
            meta: {
                requiresAuth: true
            }
        },
    ]
})
router.beforeEach((to, from) => {
    // instead of having to check every route record with
    // to.matched.some(record => record.meta.requiresAuth)
    const isLoggedIn = sessionStorage.getItem('token')
    if (to.meta.requiresAuth && isLoggedIn == null) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        return {
            path: '/',
            // save the location we were at to come back later
            query: { redirect: to.fullPath },
        }
    }
})
export default router
