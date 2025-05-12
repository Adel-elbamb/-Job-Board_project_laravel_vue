import { createRouter, createWebHistory } from 'vue-router'
import AdminDashboard from '../AdminDashboard.vue'
import AdminStats from '../AdminStats.vue'
import AdminUsers from '../AdminUsers.vue'

const routes = [
  { path: '/admin', component: AdminDashboard },
  { path: '/admin/stats', component: AdminStats },
  { path: '/admin/users', component: AdminUsers },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
