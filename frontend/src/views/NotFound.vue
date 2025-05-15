<template>
  <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="text-center p-5">
      <div class="error-container mb-4">
        <span class="error-code display-1 fw-bold text-danger">4</span>
        <span class="error-icon display-1 fw-bold text-warning">
          <i class="fas fa-exclamation-circle"></i>
        </span>
        <span class="error-code display-1 fw-bold text-danger">4</span>
      </div>

      <h1 class="display-4 fw-bold mb-3">Oops! Page Not Found</h1>
      <p class="lead text-muted mb-4">
        The page you're looking for doesn't exist or may have been moved.
        <br>Let's get you back on track.
      </p>

      <div class="d-flex justify-content-center gap-3">
        <router-link to="/" class="btn btn-primary btn-lg px-4">
          <i class="fas fa-home me-2"></i>Back to Home
        </router-link>
        <button @click="goBack" class="btn btn-outline-secondary btn-lg px-4">
          <i class="fas fa-arrow-left me-2"></i>Go Back
        </button>
      </div>

      <div class="mt-5">
        <p class="text-muted mb-2">Maybe you were looking for:</p>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
          <router-link to="/about" class="btn btn-sm btn-outline-success">About Us</router-link>
          <router-link to="/contact" class="btn btn-sm btn-outline-success">Contact</router-link>
          <router-link to="/services" class="btn btn-sm btn-outline-success">Services</router-link>
          <router-link to="/faq" class="btn btn-sm btn-outline-success">FAQ</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound',
  methods: {
    goBack ()
    {
      this.$router.go( -1 );
    }
  }
};
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

.error-container {
  position: relative;
  display: inline-block;
  animation: bounce 1.5s infinite alternate;
}

.error-icon {
  position: relative;
  display: inline-block;
  animation: pulse 1.5s infinite;
}

@keyframes bounce {
  0% {
    transform: translateY(0);
  }

  100% {
    transform: translateY(-20px);
  }
}

@keyframes pulse {
  0% {
    transform: scale(1);
    opacity: 1;
  }

  50% {
    transform: scale(1.1);
    opacity: 0.8;
  }

  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.min-vh-100 {
  min-height: 100vh;
}

.btn-primary {
  background-color: #34A853;
  border-color: #34A853;
}

.btn-primary:hover {
  background-color: #2d9145;
  border-color: #2d9145;
}

.btn-outline-success {
  color: #34A853;
  border-color: #34A853;
}

.btn-outline-success:hover {
  background-color: #34A853;
  color: white;
}
</style>