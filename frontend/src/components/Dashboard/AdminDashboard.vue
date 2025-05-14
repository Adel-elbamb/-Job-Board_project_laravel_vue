<template>
  <div class="homepage-container">
    <nav class="navbar">
      <div class="nav-left">
        <span class="logo">Jobpilot</span>
        </div>
      <div class="nav-center">
        <a href="#">Home</a>
        <a href="#">Find Job</a>
        <a href="#">Employers</a>
        <a href="#">Candidates</a>
        <router-link to="/ProfileManagement">Manage Profile</router-link>
        
  
        </div>
      <div class="nav-right">
        
        <a href="#" class="btn btn-primary">Post a Jobs</a>
        <button class="btn btn-outline-danger"  @click="logout">Logout</button>
      </div>
    </nav>

    <header class="hero-section">
      <div class="hero-content">
        <h1>Find a job that suits your interest & skills.</h1>
        <p class="subtitle">
          Aliquam vitae turpis in diam convallis finibus in at risus. Nullam
          in scelerisque leo, eget sollicitudin velit vestibulum.
        </p>
        <form class="search-form">
          <div class="form-group">
            <i class="fas fa-search"></i> <input type="text" placeholder="Job Title, Keyword..." />
          </div>
          <div class="form-group">
            <i class="fas fa-map-marker-alt"></i> <input type="text" placeholder="Your Location" />
          </div>
          <button type="submit" class="btn btn-find-job">Find Job</button>
        </form>
        
      </div>
      <div class="hero-image">
         <img src="../Dashboard/images/candidate.png"/>
      </div>
    </header>

    <div class="user-actions" v-if="isLoggedIn"> <router-link to="/ProfileManagement">Manage Profile</router-link>
      <button @click="logout">Logout</button>
    </div>

  </div>
</template>

<script>
import { useUserStore } from '@/store/user';
import { useRouter } from 'vue-router';   

export default {
  name: 'HomePage', 
  setup() {
    const userStore = useUserStore();
    const router = useRouter();
    return {
      userStore,
      router,
    };
  },
  methods: {
    logout() {
      this.userStore.logout();
      this.router.push('/login');
    },
  },
};
</script>

<style scoped>

.homepage-container {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar .logo {
  font-size: 1.8rem;
  font-weight: bold;
  color: #0d6efd; 
}

.nav-center a {
  margin: 0 1rem;
  text-decoration: none;
  color: #555;
  font-weight: 500;
}
.nav-center a:hover {
  color: #0d6efd;
}

.nav-right .btn {
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 500;
  margin-left: 0.5rem;
}
.nav-right .btn-primary {
  background-color: #0d6efd;
  color: white;
}
.nav-right .btn-primary:hover {
  background-color: #0b5ed7;
}
.nav-right .btn-outline {
  border: 1px solid #0d6efd;
  color: #0d6efd;
}
.nav-right .btn-outline:hover {
  background-color: #e7f0ff;
}


/* Hero Section Styles */
.hero-section {
  display: flex;
  align-items: center;
  justify-content: space-between; 
  padding: 4rem 2rem; 
  background-color: #f8f9fa; 
  min-height: 70vh; 
}

.hero-content {
  max-width: 50%; 
  padding-right: 2rem; 
}

.hero-content h1 {
  font-size: 2.8rem; 
  color: #2c3e50; 
  margin-bottom: 1rem;
  line-height: 1.3;
}

.hero-content .subtitle {
  font-size: 1.1rem;
  color: #555;
  margin-bottom: 2rem;
  line-height: 1.6;
}

.search-form {
  display: flex;
  gap: 1rem; 
  margin-bottom: 1.5rem;
  background-color: #fff;
  padding: 1rem;
  border-radius: 8px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.search-form .form-group {
  display: flex;
  align-items: center;
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 0.5rem 0.8rem;
  flex-grow: 1; 
}
.search-form .form-group i {
  margin-right: 0.5rem;
  color: #777;
}

.search-form input[type="text"] {
  border: none;
  outline: none;
  width: 100%;
  font-size: 1rem;
}

.search-form .btn-find-job {
  background-color: #0d6efd;
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 500;
}
.search-form .btn-find-job:hover {
  background-color: #0b5ed7;
}

.suggestions {
  font-size: 0.9rem;
  color: #555;
}
.suggestions strong {
  color: #333;
}

.hero-image {
  max-width: 45%; 
}

.hero-image img {
  max-width: 100%;
  height: auto;
  border-radius: 8px; 
}

.user-actions {
  padding: 2rem;
  text-align: center;
  background-color: #e9ecef; 
}
.user-actions a, .user-actions button {
  margin: 0.5rem;
}

.user-actions a {
  display: inline-block;
  padding: 0.6rem 1.2rem;
  font-size: 1rem;
  background-color: #0d6efd;
  color: white;
  border-radius: 8px;
  text-decoration: none;
}
.user-actions button {
  padding: 0.6rem 1.2rem;
  font-size: 1rem;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}

</style>














 