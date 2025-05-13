<!-- AdminDashboard.vue -->
<template>
  <div class="container mt-5">
    <h2 class="mb-4">📊 Dashboard Stats</h2>
    <ul class="list-group">
      <li class="list-group-item">👤 Total Users: {{ stats.total_users }}</li>
      <li class="list-group-item">💼 Total Jobs: {{ stats.total_jobs }}</li>
      <li class="list-group-item">🕒 Pending Jobs: {{ stats.pending_jobs }}</li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      stats: {
        total_users: 0,
        total_jobs: 0,
        pending_jobs: 0
      }
    };
  },
  mounted() {
    this.fetchStats();
  },
  methods: {
    async fetchStats() {
      const res = await fetch("http://127.0.0.1:8000/api/admin/stats", {
        headers: {
          Authorization: "Bearer 2|4jOEH552om4N6CFzaLMWhDLdzQ2enuUa2hMdnjBI1167cb8f",
        },
      });
      const data = await res.json();
      this.stats = data;
    },
  }
};
</script>
