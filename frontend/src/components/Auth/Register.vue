<template>
  <div class="container">
    <div class="row">
      <div class="col-12 mt-5">
        <div class="card">
          <div class="card-header">Registration Form</div>
          <div class="card-body">
            <div v-if="error" class="alert alert-danger">
              {{ error.message || error }}
              <ul v-if="error.errors">
                <li v-for="(messages, field) in error.errors" :key="field">
                  {{ field }}: {{ messages.join(", ") }}
                </li>
              </ul>
            </div>
            <form @submit.prevent="handleRegister">
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Enter your Name"
                  v-model="username"
                />
              </div>
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input
                  type="email"
                  class="form-control"
                  id="exampleInputEmail1"
                  aria-describedby="emailHelp"
                  placeholder="Enter your Email"
                  v-model="email"
                />
                <div id="emailHelp" class="form-text">
                  We'll never share your email with anyone else.
                </div>
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input
                  type="password"
                  class="form-control"
                  id="exampleInputPassword1"
                  placeholder="Enter your password"
                  v-model="password"
                />
              </div>
              <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input
                  type="password"
                  class="form-control"
                  placeholder="Confirm your password"
                  v-model="password_confirmation"
                />
              </div>
              <div class="mb-3">
                <label for="role" class="form-label">Select Role</label>
                <select v-model="role" class="form-select" id="role" required>
                  <option disabled value="">Choose...</option>
                  <option value="candidate">Candidate</option>
                  <option value="employer">Employer</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
              <div class="mb-3 form-check">
                <input
                  type="checkbox"
                  class="form-check-input"
                  id="exampleCheck1"
                />
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
              </div>
              <button type="submit" class="btn btn-primary">Register</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { useUserStore } from "@/store/user";
import { useRouter } from "vue-router";

// Configure axios
const apiBaseUrl = "http://localhost:8000/api";

export default {
  name: "Register",
  data() {
    return {
      username: "",
      email: "",
      password: "",
      password_confirmation: "",
      role: "",
      error: null, 
    };
  },
  setup() {
    const userStore = useUserStore();
    const router = useRouter();
    return { userStore, router };
  },
  methods: {
    async handleRegister() {
      try {
        this.error = null;
        
        // Use axios directly with the base URL
        const response = await axios.post(`${apiBaseUrl}/register`, {
          username: this.username,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
          role: this.role,
        }, {
          withCredentials: true,
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        });
        
        // Handle successful registration
        console.log("Registration successful:", response.data);
        
        // Optional: display success message
        alert("Registration successful! Please login.");
        
        // Redirect to login page
        this.router.push("/login");
      } catch (error) {
        // Handle validation or other errors
        if (error.response && error.response.data) {
          this.error = error.response.data;
        } else {
          this.error = "Failed to register. Please try again.";
        }
        console.error("Registration failed:", error.response?.data || error.message);
      }
    },
  },
};
</script>

<style scoped>
body {
  background-color: #F1F2F499;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.container {
  max-width: 600px;
}

.card {
  border: none;
  border-radius: 16px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
}

.card-header {
  background-color: #0d6efd;
  color: white;
  font-size: 1.5rem;
  font-weight: bold;
  border-radius: 16px 16px 0 0;
  text-align: center;
  padding: 1rem;
}

.card-body {
  padding: 2rem;
}

.form-label {
  font-weight: 600;
  color: #343a40;
  margin-top: 1rem;
}

.form-control {
  border-radius: 10px;
  padding: 0.75rem;
  border: 1px solid #ced4da;
  transition: border-color 0.3s;
}

.form-control:focus {
  border-color: #0d6efd;
  box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
}

.form-text {
  font-size: 0.85rem;
  color: #6c757d;
}

.btn-primary {
  width: 100%;
  padding: 0.75rem;
  font-weight: bold;
  background-color: #0d6efd;
  border-radius: 10px;
  border: none;
  transition: background-color 0.3s;
}

.btn-primary:hover {
  background-color: #0b5ed7;
}

.form-check-label {
  color: #495057;
}

.form-check-input:checked {
  background-color: #0d6efd;
  border-color: #0d6efd;
}

.alert-danger {
  margin-bottom: 1rem;
  padding: 0.75rem;
  border-radius: 10px;
}
</style>