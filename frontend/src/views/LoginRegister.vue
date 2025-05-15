<template>
  <div class="auth-container">
    <div class="auth-wrapper">
      <div class="auth-card">
        <!-- Notification Toasts -->
        <div v-if="successMessage" class="toast-custom toast-success">
          <i class="fas fa-check-circle me-2"></i>{{ successMessage }}
        </div>
        <div v-if="errorMessage" class="toast-custom toast-error">
          <i class="fas fa-exclamation-circle me-2"></i>{{ errorMessage }}
        </div>

        <!-- Form Header -->
        <div class="auth-header text-center mb-4">
          <div class="auth-icon mb-3">
            <i class="fas" :class="isLogin ? 'fa-sign-in-alt' : 'fa-user-plus'"></i>
          </div>
          <h3 class="auth-title">{{ isLogin ? 'Welcome Back' : 'Create Account' }}</h3>
          <p class="auth-subtitle">
            {{ isLogin ? 'Sign in to continue' : 'Get started with your account' }}
          </p>
        </div>

        <!-- Main Form -->
        <form @submit.prevent="isLogin ? handleLogin() : handleRegister()">
          <div v-if="!isLogin" class="mb-3">
            <label class="form-label">Full Name</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
              <input type="text" class="form-control" v-model="form.name" placeholder="John Doe" required />
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              <input type="email" class="form-control" v-model="form.email" placeholder="example@domain.com" required />
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input :type="showPassword ? 'text' : 'password'" class="form-control" v-model="form.password"
                placeholder="••••••••" required />
              <button class="btn btn-outline-secondary" type="button" @click="showPassword = !showPassword">
                <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
              </button>
            </div>
            <div v-if="!isLogin" class="password-strength mt-2">
              <div class="progress" style="height: 4px;">
                <div class="progress-bar" :class="passwordStrengthClass" role="progressbar"
                  :style="{ width: passwordStrength + '%' }"></div>
              </div>
              <small class="text-muted" v-if="!isLogin">Password strength: {{ passwordStrengthText }}</small>
            </div>
          </div>

          <div v-if="!isLogin" class="mb-3">
            <label class="form-label">Confirm Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input :type="showConfirmPassword ? 'text' : 'password'" class="form-control"
                v-model="form.password_confirmation" placeholder="••••••••" required />
              <button class="btn btn-outline-secondary" type="button"
                @click="showConfirmPassword = !showConfirmPassword">
                <i class="fas" :class="showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
              </button>
            </div>
          </div>

          <div v-if="!isLogin" class="mb-4">
            <label class="form-label">I am a</label>
            <div class="role-selector">
              <div class="form-check">
                <input class="form-check-input" type="radio" id="role-candidate" v-model="form.role" value="candidate"
                  required>
                <label class="form-check-label" for="role-candidate">
                  <i class="fas fa-user-tie me-1"></i> Candidate
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" id="role-employer" v-model="form.role" value="employer"
                  required>
                <label class="form-check-label" for="role-employer">
                  <i class="fas fa-briefcase me-1"></i> Employer
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" id="role-admin" v-model="form.role" value="admin" required>
                <label class="form-check-label" for="role-admin">
                  <i class="fas fa-user-shield me-1"></i> Administrator
                </label>
              </div>
            </div>
          </div>

          <div v-if="isLogin" class="d-flex justify-content-between mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="rememberMe" v-model="rememberMe">
              <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <router-link to="/forgot-password" class="text-decoration-none forgot-password">
              Forgot password?
            </router-link>
          </div>

          <button type="submit" class="btn btn-primary w-100 mb-3" :disabled="loading">
            <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            <i v-else class="fas" :class="isLogin ? 'fa-sign-in-alt' : 'fa-user-plus'"></i>
            {{ loading ? (isLogin ? 'Signing In...' : 'Registering...') : (isLogin ? 'Sign In' : 'Sign Up') }}
          </button>

          <div class="text-center auth-footer">
            <span class="text-muted">
              {{ isLogin ? "Don't have an account?" : "Already have an account?" }}
            </span>
            <button class="btn btn-link p-0 ms-2" @click="toggleForm">
              {{ isLogin ? 'Sign up' : 'Sign in' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'AuthForm',
  data ()
  {
    return {
      isLogin: true,
      showPassword: false,
      showConfirmPassword: false,
      loading: false,
      rememberMe: false,
      successMessage: '',
      errorMessage: '',
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'candidate',
      }
    };
  },
  computed: {
    passwordStrength ()
    {
      if ( !this.form.password ) return 0;
      let strength = 0;

      // Length check
      if ( this.form.password.length > 5 ) strength += 20;
      if ( this.form.password.length > 8 ) strength += 20;

      // Complexity checks
      if ( /[A-Z]/.test( this.form.password ) ) strength += 20;
      if ( /\d/.test( this.form.password ) ) strength += 20;
      if ( /[^A-Za-z0-9]/.test( this.form.password ) ) strength += 20;

      return Math.min( strength, 100 );
    },
    passwordStrengthText ()
    {
      const strength = this.passwordStrength;
      if ( strength < 40 ) return 'Weak';
      if ( strength < 70 ) return 'Moderate';
      if ( strength < 90 ) return 'Strong';
      return 'Very Strong';
    },
    passwordStrengthClass ()
    {
      const strength = this.passwordStrength;
      if ( strength < 40 ) return 'bg-danger';
      if ( strength < 70 ) return 'bg-warning';
      return 'bg-success';
    }
  },
  methods: {
    toggleForm ()
    {
      this.isLogin = !this.isLogin;
      this.clearMessages();
      this.resetForm();
    },
    clearMessages ()
    {
      this.successMessage = '';
      this.errorMessage = '';
    },
    resetForm ()
    {
      this.form = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'candidate',
      };
      this.loading = false;
    },
    async handleRegister ()
    {
      this.loading = true;
      try
      {
        const res = await axios.post( '/api/register', this.form );
        this.successMessage = res.data.message || 'Registration successful!';
        this.errorMessage = '';
        setTimeout( () =>
        {
          this.successMessage = '';
          this.toggleForm();
        }, 3000 );
      } catch ( error )
      {
        this.errorMessage = error.response?.data?.message || 'Registration failed. Please try again.';
        this.successMessage = '';
      } finally
      {
        this.loading = false;
      }
    },
    async handleLogin ()
    {
      this.loading = true;
      try
      {
        const res = await axios.post( '/api/login', {
          email: this.form.email,
          password: this.form.password,
          remember: this.rememberMe
        } );

        localStorage.setItem( 'token', res.data.token );
        localStorage.setItem( 'userRole', res.data.user.role );
        localStorage.setItem( 'userName', res.data.user.name );
        this.successMessage = res.data.message || 'Login successful!';
        this.errorMessage = '';

        // Redirect based on role
        let redirectPath = '/profile';
        switch ( res.data.user.role )
        {
          case 'admin':
            redirectPath = '/dashboard';
            break;
          case 'employer':
            redirectPath = '/profile';
            break;
          case 'candidate':
            redirectPath = '/profile';
            break;
        }

        setTimeout( () =>
        {
          this.$router.push( redirectPath );
        }, 1500 );
      } catch ( error )
      {
        this.errorMessage = error.response?.data?.message || 'Login failed. Please check your credentials.';
        this.successMessage = '';
      } finally
      {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

.auth-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f8f9fa, #e9ecef);
  padding: 20px;
}

.auth-wrapper {
  width: 100%;
  max-width: 450px;
}

.auth-card {
  background: white;
  border-radius: 16px;
  padding: 2.5rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(52, 168, 83, 0.2);
}

.auth-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 6px;
  background: linear-gradient(to right, #34A853, #2d9145);
}

.auth-header {
  margin-bottom: 2rem;
}

.auth-icon {
  width: 70px;
  height: 70px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(52, 168, 83, 0.1);
  border-radius: 50%;
  color: #34A853;
  font-size: 1.75rem;
}

.auth-title {
  font-weight: 700;
  color: #2d3748;
  margin-bottom: 0.5rem;
  font-family: 'Poppins', sans-serif;
}

.auth-subtitle {
  color: #718096;
  font-size: 0.9rem;
}

.form-label {
  font-weight: 500;
  color: #4a5568;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

.input-group-text {
  background-color: #f7fafc;
  border-right: none;
  color: #718096;
}

.form-control {
  border-left: none;
  padding: 0.75rem 1rem;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.form-control:focus {
  box-shadow: 0 0 0 0.25rem rgba(52, 168, 83, 0.25);
  border-color: #34A853;
}

.btn-primary {
  background-color: #34A853;
  border-color: #34A853;
  padding: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
}

.btn-primary:hover:not(:disabled) {
  background-color: #2d9145;
  border-color: #2d9145;
  transform: translateY(-2px);
}

.btn-primary:disabled {
  opacity: 0.8;
}

.btn-outline-secondary {
  border-color: #e2e8f0;
}

.btn-link {
  color: #34A853;
  font-weight: 500;
  text-decoration: none;
}

.btn-link:hover {
  color: #2d9145;
  text-decoration: underline;
}

.forgot-password {
  color: #34A853;
  font-weight: 500;
}

.forgot-password:hover {
  color: #2d9145;
  text-decoration: underline;
}

.role-selector {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-top: 0.5rem;
}

.form-check {
  display: flex;
  align-items: center;
}

.form-check-input {
  margin-right: 0.5rem;
  width: 1.1em;
  height: 1.1em;
}

.form-check-input:checked {
  background-color: #34A853;
  border-color: #34A853;
}

.form-check-label {
  display: flex;
  align-items: center;
  font-size: 0.95rem;
  cursor: pointer;
}

.password-strength {
  margin-left: 5px;
}

.toast-custom {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-weight: 500;
  font-size: 0.9rem;
  color: white;
  animation: slideDown 0.4s ease;
  z-index: 1000;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  display: flex;
  align-items: center;
}

.toast-success {
  background: linear-gradient(to right, #34A853, #48BB78);
}

.toast-error {
  background: linear-gradient(to right, #E53E3E, #F56565);
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translate(-50%, -20px);
  }

  to {
    opacity: 1;
    transform: translate(-50%, 0);
  }
}

.auth-footer {
  margin-top: 1.5rem;
  font-size: 0.9rem;
}

@media (max-width: 576px) {
  .auth-card {
    padding: 1.5rem;
  }

  .auth-title {
    font-size: 1.5rem;
  }

  .role-selector {
    gap: 0.5rem;
  }

  .auth-icon {
    width: 60px;
    height: 60px;
    font-size: 1.5rem;
  }
}
</style>