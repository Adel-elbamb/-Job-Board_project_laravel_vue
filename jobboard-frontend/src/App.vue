// File: src/App.vue
<template>
  <div class="container py-4">
    <header class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3">JobPilot</h1>
      <nav>
        <router-link class="me-3 text-decoration-none" to="/admin">Jobs</router-link>
        <router-link class="me-3 text-decoration-none" to="/admin/stats">Analytics</router-link>
        <router-link class="text-decoration-none" to="/admin/users">Users</router-link>
      </nav>
    </header>

    <router-view />
  </div>
</template>

<script setup>
</script>

<style>
/* Bootstrap is imported globally from main.js */
</style>
