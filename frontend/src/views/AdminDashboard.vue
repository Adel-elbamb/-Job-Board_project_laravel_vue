
<template>
  <div>
    <h2 class="h4 mb-4">Pending Jobs</h2>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Title</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="jobs.length === 0">
          <td colspan="4" class="text-center">No pending jobs found.</td>
        </tr>
        <tr v-for="(job, index) in jobs" :key="job.id">
          <td>{{ index + 1 }}</td>
          <td>{{ job.title }}</td>
          <td>{{ job.status }}</td>
          <td>
            <button class="btn btn-success btn-sm me-2" @click="approveJob(job.id)">Approve</button>
            <button class="btn btn-danger btn-sm" @click="rejectJob(job.id)">Reject</button>
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
    };
  },
  mounted() {
    this.fetchJobs();
  },
  methods: {
    async fetchJobs() {
      const res = await fetch('http://127.0.0.1:8000/api/jobs?status=pending');
      const data = await res.json();
      this.jobs = data;
    },
    async approveJob(id) {
      await fetch(`http://127.0.0.1:8000/api/jobs/${id}/approve`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: 'Bearer 2|4jOEH552om4N6CFzaLMWhDLdzQ2enuUa2hMdnjBI1167cb8f', // ✨ بدلي التوكين
        },
      });
      this.fetchJobs(); // نرجع نجيب الوظائف بعد التعديل
    },
    async rejectJob(id) {
      await fetch(`http://127.0.0.1:8000/api/jobs/${id}/reject`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: 'Bearer 2|4jOEH552om4N6CFzaLMWhDLdzQ2enuUa2hMdnjBI1167cb8f',
        },
      });
      this.fetchJobs();
    },
  },
};
</script>