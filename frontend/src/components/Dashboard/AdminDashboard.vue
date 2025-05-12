<template>
  <div class="dashboard">
    <h2>Welcome Admin</h2>
    <p>Admin dashboard</p>
    <router-link to="/ProfileManagement">Manage Profile</router-link>
    <button @click="logout">Logout</button>
  </div>
</template>

<script>
import { useUserStore } from '@/store/user';
import { useRouter } from 'vue-router';

export default {
  name: 'EmployerDashboard',
  setup() {
    const userStore = useUserStore();
    const router = useRouter();
    return { userStore, router };
  },
  methods: {
    logout() {
      this.userStore.logout();
      this.router.push('/auth/login');
    },
  },
};
</script>

<style scoped>
.dashboard {
  max-width: 700px;
  margin: 80px auto;
  padding: 2rem;
  background-color: #ffffff;
  border-radius: 16px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
  text-align: center;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.dashboard h2 {
  font-size: 2rem;
  color: #0d6efd;
  margin-bottom: 1rem;
}

.dashboard p {
  font-size: 1.1rem;
  color: #495057;
  margin-bottom: 2rem;
}

.dashboard a {
  display: inline-block;
  margin-bottom: 1.5rem;
  padding: 0.6rem 1.2rem;
  font-size: 1rem;
  background-color: #0d6efd;
  color: white;
  border-radius: 8px;
  text-decoration: none;
  transition: background-color 0.3s;
}

.dashboard a:hover {
  background-color: #0b5ed7;
}

.dashboard button {
  padding: 0.6rem 1.2rem;
  font-size: 1rem;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.dashboard button:hover {
  background-color: #c82333;
}

</style>