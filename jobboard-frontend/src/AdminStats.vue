<template>
  <div class="container">
    <h1>📊 Platform Analytics</h1>
    <div class="stats">
      <div class="card">
        <h2>Jobs</h2>
        <p>{{ stats.jobs }}</p>
      </div>
      <div class="card">
        <h2>Users</h2>
        <p>{{ stats.users }}</p>
      </div>
      <div class="card">
        <h2>Applications</h2>
        <p>{{ stats.applications }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      stats: {
        jobs: 0,
        users: 0,
        applications: 0,
      }
    };
  },
  mounted() {
    fetch("http://127.0.0.1:8000/api/admin/stats", {
      headers: {
        Authorization: `Bearer YOUR_ADMIN_TOKEN`
      }
    })
      .then(res => res.json())
      .then(data => {
        this.stats = data;
      });
  }
}
</script>

<style scoped>
.container {
  padding: 20px;
  font-family: Arial, sans-serif;
}
.stats {
  display: flex;
  gap: 20px;
  margin-top: 20px;
}
.card {
  flex: 1;
  background: #f5f5f5;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
  box-shadow: 0 0 5px #ccc;
}
.card h2 {
  margin-bottom: 10px;
}
</style>
