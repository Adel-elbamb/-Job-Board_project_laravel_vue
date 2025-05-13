
<template>
  <div>
    <h2 class="h4 mb-4">Platform Analytics</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card text-bg-light">
          <div class="card-body">
            <h5 class="card-title">Total Jobs</h5>
            <p class="card-text fs-4">{{ stats.jobs }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-bg-light">
          <div class="card-body">
            <h5 class="card-title">Total Applications</h5>
            <p class="card-text fs-4">{{ stats.applications }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-bg-light">
          <div class="card-body">
            <h5 class="card-title">Total Users</h5>
            <p class="card-text fs-4">{{ stats.users }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const stats = ref({})
const token = '1|ApGiaGqteOE9y2GXts7ITjPfZ6LG3lzNktTDZsWV73e0a8fa'

onMounted(() => {
  fetch('http://127.0.0.1:8000/api/admin/stats', {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
    .then(res => res.json())
    .then(data => {
      stats.value = data
    })
})
</script>
