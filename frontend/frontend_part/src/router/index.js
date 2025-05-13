import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '@/pages/Employer/Dashboard.vue';
import CreateJob from '@/pages/Employer/CreateJob.vue';
import EditJob from '@/pages/Employer/EditJob.vue';

const routes = [
  { path: '/dashboard', name: 'Dashboard', component: Dashboard },
  { path: '/jobs/create', name: 'CreateJob', component: CreateJob },
  { path: '/jobs/:id/edit', name: 'EditJob', component: EditJob },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
