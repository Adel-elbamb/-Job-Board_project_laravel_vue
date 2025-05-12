<template>
  <div class="container">
    <div class="row">
      <div class="col-12 mt-5">
        <div class="card">
          <div class="card-header">Forgot Password</div>
          <div class="card-body">
            <form>
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label"
                  >Email address</label
                >
                <input
                  type="email"
                  class="form-control"
                  id="exampleInputEmail1"
                  aria-describedby="emailHelp"
                  placeholder="Enter your Email address"
                  required
                />
              </div>
              <button type="submit" class="btn btn-primary w-100">
                Send Reset Link
              </button>

              <router-link to="/login" class="back-link"
                >Back to Login</router-link
              >
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
  margin-bottom: 0.5rem;
  display: block;
}

.form-control {
  border-radius: 10px;
  padding: 0.75rem;
  width: 100%;
  border: 1px solid #ced4da;
  transition: border-color 0.3s;
}

.form-control:focus {
  border-color: #0d6efd;
  box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
}

.btn-primary {
  padding: 0.75rem;
  font-weight: bold;
  background-color: #0d6efd;
  border-radius: 10px;
  border: none;
  margin-top: 1rem;
  transition: background-color 0.3s;
}

.btn-primary:hover {
  background-color: #0b5ed7;
}

.back-link {
  display: block;
  text-align: center;
  margin-top: 1rem;
  color: #0d6efd;
  font-weight: 500;
  text-decoration: none;
}

.back-link:hover {
  color: #084298;
  text-decoration: underline;
}
</style>

<script>
import axios from "axios";
import { useRouter } from "vue-router";

export default {
  name: "ForgotPassword",
  data() {
    return {
      email: "",
    };
  },
  setup() {
    const router = useRouter();
    return { router };
  },
  methods: {
    async handleForgotPassword() {
      try {
        await axios.post("/api/forgot-password", { email: this.email });
        alert("Reset link sent to your email");
        this.router.push("/auth/login");
      } catch (error) {
        console.error("Forgot password failed", error);
      }
    },
  },
};
</script>
