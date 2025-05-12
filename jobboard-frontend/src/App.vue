<template>
  <div class="app">
    <h1 class="title">Admin Panel</h1>

    <nav class="navbar">
      <router-link to="/admin">Jobs</router-link>
      <router-link to="/admin/stats">Analytics</router-link>
      <router-link to="/admin/users">Users</router-link>
    </nav>

    <router-view />
  </div>
</template>

<script setup>
// مفيش حاجة هنا
</script>

<style scoped>
.app {
  padding: 20px;
  font-family: Arial, sans-serif;
}

.title {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

.navbar {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-bottom: 30px;
}

.navbar a {
  text-decoration: none;
  color: #333;
  font-weight: bold;
  padding: 6px 12px;
  border-radius: 4px;
  transition: background 0.2s;
}

.navbar a:hover {
  background: #eee;
}
</style>
