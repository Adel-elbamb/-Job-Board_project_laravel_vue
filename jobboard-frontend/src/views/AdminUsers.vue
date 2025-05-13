
<template>
  <div>
    <h2 class="h4 mb-4">User Management</h2>
    <table class="table table-hover">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Email</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="users.length === 0">
          <td colspan="4" class="text-center">No users found.</td>
        </tr>
        <tr v-for="(user, index) in users" :key="user.id">
          <td>{{ index + 1 }}</td>
          <td>{{ user.email }}</td>
          <td>
            <span :class="user.is_active ? 'text-success' : 'text-danger'">
              {{ user.is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>
            <button
              v-if="user.is_active"
              class="btn btn-danger btn-sm"
              @click="toggle(user.id, false)"
            >
              Deactivate
            </button>
            <button
              v-else
              class="btn btn-success btn-sm"
              @click="toggle(user.id, true)"
            >
              Activate
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const users = ref([])
const token = '1|ApGiaGqteOE9y2GXts7ITjPfZ6LG3lzNktTDZsWV73e0a8fa'

const fetchUsers = () => {
  fetch('http://127.0.0.1:8000/api/users', {
    headers: {
      Authorization: `Bearer ${token}`
    }
  })
    .then(res => res.json())
    .then(data => {
      users.value = data
    })
}

const toggleUser = (id, isActive) => {
  const url = `http://127.0.0.1:8000/api/users/${id}/${isActive ? 'deactivate' : 'activate'}`
  fetch(url, {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${token}`
    }
  }).then(() => fetchUsers())
}

onMounted(fetchUsers)
</script>
