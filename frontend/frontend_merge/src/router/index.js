import { createRouter, createWebHistory } from 'vue-router';

import Register from '../components/Auth/Register.vue';
import Login from '../components/Auth/login.vue';
import ForgetPassword from '../components/Auth/ForgetPassword.vue';

import EmployerDashboard from '../pages/Employer/EmployerDashboard.vue';
import CandidateDashboard from '../pages/candidate/candidatedashboard.vue';
import AdminDashboard from '../pages/admin/AdminDashboard.vue';


import ProfileManagement from '../components/Profile/ProfileManagement.vue';

import CreateJob from '@/pages/Employer/CreateJob.vue';
import EditJob from '@/pages/Employer/EditJob.vue';

const routes = [
  // { path: '/', name: 'Home', component: HelloWorld },
  { path: '/register', name: 'Register', component: Register },
  { path: '/login', name: 'Login', component: Login },
  { path: '/forgetpassword', name: 'ForgetPassword', component: ForgetPassword },

  { path: '/dashboard/employerdashboard', name: 'EmployerDashboard', component: EmployerDashboard },
  { path: '/dashboard/candidatedashboard', name: 'CandidateDashboard', component: CandidateDashboard },
  { path: '/dashboard/admindashboard', name: 'AdminDashboard', component: AdminDashboard },

  { path: '/profilemanagement', name: 'ProfileManagement', component: ProfileManagement },

  { path: '/jobs/create', name: 'CreateJob', component: CreateJob },
  { path: '/jobs/:id/edit', name: 'EditJob', component: EditJob },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;




















