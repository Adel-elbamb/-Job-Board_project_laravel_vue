
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

<script setup>
import { ref, onMounted } from 'vue'

const jobs = ref([])
const token = '1|ApGiaGqteOE9y2GXts7ITjPfZ6LG3lzNktTDZsWV73e0a8fa' // ← التوكن بتاع الأدمن

const fetchJobs = () => {
  fetch('http://127.0.0.1:8000/api/jobs?status=pending', {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
    .then(res => res.json())
    .then(data => {
      jobs.value = data
    })
}

const approveJob = (id) => {
  fetch(`http://127.0.0.1:8000/api/jobs/${id}/approve`, {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${token}`
    }
  }).then(() => fetchJobs())
}

const rejectJob = (id) => {
  fetch(`http://127.0.0.1:8000/api/jobs/${id}/reject`, {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${token}`
    }
  }).then(() => fetchJobs())
}

onMounted(fetchJobs)
</script>