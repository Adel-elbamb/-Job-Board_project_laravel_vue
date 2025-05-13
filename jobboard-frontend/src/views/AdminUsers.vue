<template>
  <div class="container mt-5">
    <h2 class="mb-4">👥 User Management</h2>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <table v-else class="table table-striped table-hover table-bordered shadow-sm">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Status</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>
            <span
              class="badge"
              :class="user.is_active ? 'bg-success' : 'bg-secondary'"
            >
              {{ user.is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td class="text-center">
            <button
              class="btn btn-sm"
              :class="user.is_active ? 'btn-danger' : 'btn-success'"
              @click="toggleUser(user)"
            >
              {{ user.is_active ? 'Deactivate' : 'Activate' }}
            </button>
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
      users: [],
      loading: true,
    };
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    async fetchUsers() {
      this.loading = true;
      const res = await fetch("http://127.0.0.1:8000/api/users", {
        headers: {
          Authorization: "Bearer 2|4jOEH552om4N6CFzaLMWhDLdzQ2enuUa2hMdnjBI1167cb8f", 
        },
      });
      this.users = await res.json();
      this.loading = false;
    },
    async toggleUser(user) {
      const endpoint = user.is_active ? "deactivate" : "activate";

      await fetch(`http://127.0.0.1:8000/api/users/${user.id}/${endpoint}`, {
        method: "POST",
        headers: {
          Authorization: "Bearer 2|4jOEH552om4N6CFzaLMWhDLdzQ2enuUa2hMdnjBI1167cb8f",
        },
      });

      this.fetchUsers();
    },
  },
};
</script>

<style scoped>
.table th,
.table td {
  vertical-align: middle;
}
</style>
