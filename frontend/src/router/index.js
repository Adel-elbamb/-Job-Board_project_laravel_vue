import { createRouter, createMemoryHistory, createWebHistory } from "vue-router";

import Register from '../components/Auth/Register.vue';
import login from '../components/Auth/login.vue';
import HelloWorld from '../components/HelloWorld.vue';
import ForgetPassword from '../components/Auth/ForgetPassword.vue';
import EmployerDashboard from '../components/Dashboard/EmployerDashboard.vue';
import CandidateDashboard from '../components/Dashboard/candidatedashboard.vue';
import AdminDashboard from '../components/Dashboard/AdminDashboard.vue';
import ProfileManagement from '../components/Profile/ProfileManagement.vue'





const routers=[
    {
        path: '/',
        name:'home',
        component: HelloWorld
    },
    {
        path: '/register',
        name: 'Register',
        component : Register
    },
    {
        path: '/login',
        name: 'login',
        component : login
    },
    {
        path: '/forgetpassword',
        name: 'ForgetPassword',
        component : ForgetPassword
    },
    {
        path: '/dashboard/employerdashboard',
        name: 'EmployerDashboard',
        component : EmployerDashboard
    },
    {
        path: '/dashboard/candidatedashboard',
        name: 'CandidateDashboard',
        component : CandidateDashboard
    },
    {
        path: '/dashboard/admindashboard',
        name: 'AdminDashboard',
        component : AdminDashboard
    },
    {
        path: '/profilemanagement',
        name: 'ProfileManagement',
        component : ProfileManagement
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes:routers
});


export default router;