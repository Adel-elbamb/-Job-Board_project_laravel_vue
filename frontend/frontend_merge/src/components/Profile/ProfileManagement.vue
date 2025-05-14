<template>
  <div class="profile-container">
    <h2>Profile Management</h2>
    <form @submit.prevent="handleUpdateProfile">
      <input type="text" placeholder="Username" required />
      <input type="email" placeholder="Email" required />
      <input type="password" placeholder="New Password" />
      <button type="submit">Update Profile</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import { useUserStore } from '@/store/user';
import { useRouter } from 'vue-router';

export default {
  name: 'ProfileManagement',
  data() {
    return {
      username: '',
      email: '',
      password: '',
    };
  },
  setup() {
    const userStore = useUserStore();
    const router = useRouter();
    return { userStore, router };
  },
  mounted() {
    // Fetch current user data
    this.username = this.userStore.user?.username || '';
    this.email = this.userStore.user?.email || '';
  },
  methods: {
    async handleUpdateProfile() {
      try {
        await axios.put('/api/profile', {
          username: this.username,
          email: this.email,
          password: this.password,
        }, {
          headers: { Authorization: `Bearer ${this.userStore.token}` },
        });
        alert('Profile updated successfully');
      } catch (error) {
        console.error('Profile update failed', error);
      }
    },
  },
};
</script>

<style scoped>
.profile-container {
  max-width: 600px;
  margin: 80px auto;
  padding: 2.5rem;
  background-color: #ffffff;
  border-radius: 16px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  text-align: center;
}

.profile-container h2 {
  font-size: 2rem;
  margin-bottom: 2rem;
  color: #0d6efd;
  font-weight: bold;
}

.profile-container form {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

.profile-container input {
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 10px;
  font-size: 1rem;
  transition: border-color 0.3s;
}

.profile-container input:focus {
  outline: none;
  border-color: #0d6efd;
  box-shadow: 0 0 6px rgba(13, 110, 253, 0.3);
}

.profile-container button {
  padding: 0.75rem;
  font-size: 1rem;
  background-color: #0d6efd;
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.3s;
}

.profile-container button:hover {
  background-color: #0b5ed7;
}

</style>