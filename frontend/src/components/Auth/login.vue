<template>
  <div class="container">
    <div class="row">
      <div class="col-12 mt-5">
        <div class="card">
          <div class="card-header">Login Form</div>
          <div class="card-body">
            <div v-if="error" class="alert alert-danger">
              <p>{{ error.message || "Failed to login, please try again" }}</p>
              <ul v-if="error.errors">
                <li v-for="(messages, field) in error.errors" :key="field">
                  {{ field }}: {{ messages.join(", ") }}
                </li>
              </ul>
            </div>
            <form @submit.prevent="handleLogin">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input
                  type="email"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Enter your Email"
                  v-model="email"
                  required
                />
              </div>

              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input
                  type="password"
                  class="form-control"
                  id="exampleInputPassword1"
                  placeholder="Enter your password"
                  v-model="password"
                  required
                />
              </div>
              <div class="mb-3 form-check">
                <input
                  type="checkbox"
                  class="form-check-input"
                  id="exampleCheck1"
                />
                <label class="form-check-label" for="exampleCheck1">Remember me</label>
              </div>

              <button type="submit" class="btn btn-primary">Login</button>
              
              <div class="mt-3">
                <router-link to="/register" class="me-3">Register</router-link>
                <router-link to="/ForgetPassword">Forgot Password?</router-link>
              </div>
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
  name: "Login",
  data() {
    return {
      email: "",
      password: "",
      error: null,
    };
  },
  setup() {
    const userStore = useUserStore();
    const router = useRouter();
    return { userStore, router };
  },
  methods: {
    async handleLogin() {
      try {
        this.error = null;
        

        const response = await axios.post(`${apiBaseUrl}/login`, {
          email: this.email,
          password: this.password,
        }, {
          withCredentials: true,
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        });
        

        console.log("Login successful:", response.data);
        

        this.userStore.login(response.data.data.token, response.data.data.role);
        // console.log("Login response:", response.data.data.token);
        // console.log("Logged in role:", response.data.role);
        
        if (response.data.data.role === "admin") {
          this.router.push("/dashboard/admindashboard");
        } else if (response.data.data.role === "employer") {
          this.router.push("/dashboard/employerdashboard");
        } else {
          this.router.push("/dashboard/candidatedashboard");
        }
      } catch (error) {
        if (error.response && error.response.data) {
          this.error = error.response.data;
        } else {
          this.error = { message: "Failed to login. Please try again." };
        }
        console.error("Login failed:", error.response?.data || error.message);
      }
    },
  },
};
</script>

<style scoped>
.alert-danger {
  margin-bottom: 1rem;
  padding: 0.75rem;
  border-radius: 10px;
}

body {
  background-color: #e9f0f7;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  margin: 0;
  padding: 0;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.container {
  max-width: 500px;
}

.card {
  border: none;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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

.btn-primary {
  width: 100%;
  padding: 0.75rem;
  font-weight: bold;
  background-color: #0d6efd;
  border-radius: 10px;
  border: none;
  transition: background-color 0.3s;
  margin-bottom: 1rem;
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

a {
  display: inline-block;
  margin-right: 15px;
  color: #0d6efd;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s;
}

a:hover {
  color: #084298;
  text-decoration: underline;
}
</style>