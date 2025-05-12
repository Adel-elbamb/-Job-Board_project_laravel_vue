<template>
  <div class="container">
    <div class="row">
      <div class="col-12 mt-5">
        <div class="card">
          <div class="card-header">Login Form</div>
          <div class="card-body">
            <form>
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">
                  User Name</label
                >

                <input
                  type="text"
                  class="form-control"
                  id="exampleInputUsername"
                  placeholder="Enter your UserName"
                />
              </div>

              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"
                  >Password</label
                >

                <input
                  type="password"
                  class="form-control"
                  id="exampleInputPassword1"
                  placeholder="Enter your password"
                />
              </div>
              <div class="mb-3 form-check">
                <input
                  type="checkbox"
                  class="form-check-input"
                  id="exampleCheck1"
                />

                <label class="form-check-label" for="exampleCheck1"
                  >Remember me</label
                >
              </div>

              <button type="submit" class="btn btn-primary">Login</button>

              <router-link to="/register">Register</router-link>

              <router-link to="/ForgetPassword">Forgot Password?</router-link>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
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

<script>
import axios from "axios";
import { useUserStore } from "@/store/user";
import { useRouter } from "vue-router";

export default {
  name: "Login",
  data() {
    return {
      email: "",
      password: "",
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
        const response = await axios.post("/api/login", {
          username: this.username,
          password: this.password,
        });
        this.userStore.login(response.data.token, response.data.role);
        if (response.data.role === "admin") {
          this.router.push("/dashboard/admin");
        } else if (response.data.role === "employer") {
          this.router.push("/dashboard/employer");
        } else {
          this.router.push("/dashboard/candidate");
        }
      } catch (error) {
        console.error("Login failed", error);
      }
    },
  },
};
</script>
