<template>
  <div class="container">
    <h1>👤 User Management</h1>

    <table v-if="users.length" class="user-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Email</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.is_active ? 'Active' : 'Inactive' }}</td>
          <td>
            <button v-if="user.is_active" @click="deactivate(user.id)" class="deactivate">Deactivate</button>
            <button v-else @click="activate(user.id)" class="activate">Activate</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-else>Loading users...</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
    };
  },
  mounted() {
    fetch('http://127.0.0.1:8000/api/users', {
      headers: {
        Authorization: `Bearer YOUR_ADMIN_TOKEN`
      }
    })
      .then(res => res.json())
      .then(data => {
        this.users = data;
      });
  },
  methods: {
    activate(id) {
      fetch(`http://127.0.0.1:8000/api/users/${id}/activate`, {
        method: 'POST',
        headers: {
          Authorization: `Bearer YOUR_ADMIN_TOKEN`
        }
      }).then(() => this.refresh());
    },
    deactivate(id) {
      fetch(`http://127.0.0.1:8000/api/users/${id}/deactivate`, {
        method: 'POST',
        headers: {
          Authorization: `Bearer YOUR_ADMIN_TOKEN`
        }
      }).then(() => this.refresh());
    },
    refresh() {
      this.mounted(); // إعادة تحميل المستخدمين
    }
  }
};
</script>

<style scoped>
.container {
  padding: 20px;
  font-family: Arial, sans-serif;
}
.user-table {
  width: 100%;
  border-collapse: collapse;
}
.user-table th, .user-table td {
  border: 1px solid #ccc;
  padding: 10px;
}
.deactivate {
  background: #dc3545;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
}
.activate {
  background: #28a745;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
}
</style>
