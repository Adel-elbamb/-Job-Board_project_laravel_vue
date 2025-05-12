<template>
  <div class="container">
    <h1>📋 Pending Jobs</h1>

    <div v-if="loading" class="loading">Loading...</div>

    <table v-else class="job-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Title</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="job in jobs" :key="job.id">
          <td>{{ job.id }}</td>
          <td>{{ job.title }}</td>
          <td>{{ job.status }}</td>
          <td>
            <button class="approve" @click="approveJob(job.id)">Approve</button>
            <button class="reject" @click="rejectJob(job.id)">Reject</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      jobs: [],
      loading: true,
    };
  },
  methods: {
    async fetchJobs() {
      try {
        const res = await fetch('http://127.0.0.1:8000/api/jobs?status=pending', {
          headers: {
            Authorization: `Bearer YOUR_ADMIN_TOKEN`, 
          },
        });
        const data = await res.json();
        this.jobs = data;
      } catch (error) {
        console.error(error);
      } finally {
        this.loading = false;
      }
    },
    async approveJob(id) {
      await fetch(`http://127.0.0.1:8000/api/jobs/${id}/approve`, {
        method: 'POST',
        headers: {
          Authorization: `Bearer YOUR_ADMIN_TOKEN`,
        },
      });
      this.fetchJobs();
    },
    async rejectJob(id) {
      await fetch(`http://127.0.0.1:8000/api/jobs/${id}/reject`, {
        method: 'POST',
        headers: {
          Authorization: `Bearer YOUR_ADMIN_TOKEN`,
        },
      });
      this.fetchJobs();
    },
  },
  mounted() {
    this.fetchJobs();
  },
};
</script>

<style scoped>
.container {
  padding: 20px;
  font-family: Arial, sans-serif;
}
h1 {
  margin-bottom: 20px;
}
.loading {
  font-style: italic;
}
.job-table {
  width: 100%;
  border-collapse: collapse;
}
.job-table th,
.job-table td {
  border: 1px solid #ccc;
  padding: 8px 12px;
  text-align: left;
}
.job-table th {
  background-color: #f5f5f5;
}
button {
  margin-right: 8px;
  padding: 6px 12px;
  border: none;
  cursor: pointer;
  border-radius: 4px;
}
.approve {
  background-color: #28a745;
  color: white;
}
.reject {
  background-color: #dc3545;
  color: white;
}
</style>
