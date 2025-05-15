<template>
  <div class="container-fluid py-4">
    <div class="row">
      <!-- Left Sidebar -->
      <div class="col-lg-3 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <div class="position-relative mx-auto" style="width: 150px; height: 150px;">
              <img :src="`http://localhost:8000/storage/${profile.profile_picture}`"
                class="rounded-circle img-thumbnail w-100 h-100" alt="Profile Picture">
              <button @click="goToEdit" class="btn btn-sm btn-success position-absolute bottom-0 end-0 rounded-circle">
                <i class="fas fa-pencil-alt"></i>
              </button>
            </div>

            <h3 class="mt-3 mb-1">{{ user.name }}</h3>
            <span class="badge bg-success bg-opacity-10 text-success">{{ user.role | capitalize }}</span>

            <hr class="my-4">

            <div class="d-grid gap-2">
              <button @click="goToEdit" class="btn btn-success">
                <i class="fas fa-user-edit me-2"></i>Edit Profile
              </button>

              <button v-if="user.role === 'employer'" @click="goToJobs" class="btn btn-outline-dark">
                <i class="fas fa-briefcase me-2"></i>My Jobs
              </button>

              <button v-if="user.role === 'admin'" @click="goToDashboard" class="btn btn-outline-dark">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
              </button>
            </div>

            <hr class="my-4">

            <div class="progress mb-3" style="height: 8px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: 80%"></div>
            </div>
            <small class="text-muted">Profile 80% complete</small>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-user-circle me-2 text-success"></i>Personal Information</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                  <h6 class="text-muted mb-2"><i class="fas fa-envelope me-2"></i>Email</h6>
                  <p class="mb-0">{{ user.email }}</p>
                </div>
              </div>

              <div class="col-md-6">
                <div class="p-3 bg-light rounded">
                  <h6 class="text-muted mb-2"><i class="fas fa-phone me-2"></i>Phone</h6>
                  <p class="mb-0">{{ profile.phone || 'Not specified' }}</p>
                </div>
              </div>

              <div class="col-12">
                <div class="p-3 bg-light rounded">
                  <h6 class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2"></i>Address</h6>
                  <p class="mb-0">{{ profile.address || 'Not specified' }}</p>
                </div>
              </div>

              <div class="col-12">
                <div class="p-3 bg-light rounded">
                  <h6 class="text-muted mb-2"><i class="fas fa-quote-left me-2"></i>Bio</h6>
                  <p class="mb-0">{{ profile.bio || 'No bio added yet' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Sidebar -->
      <div class="col-lg-3 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-rocket me-2 text-success"></i>Quick Actions</h5>
          </div>
          <div class="card-body">
            <div class="list-group list-group-flush">
              <a @click="goToEdit" class="list-group-item list-group-item-action d-flex align-items-center">
                <i class="fas fa-user-edit text-success me-3"></i>
                <span>Update Profile</span>
              </a>

              <a v-if="user.role === 'employer'" @click="goToJobs"
                class="list-group-item list-group-item-action d-flex align-items-center">
                <i class="fas fa-briefcase text-success me-3"></i>
                <span>Manage Jobs</span>
              </a>

              <a v-if="user.role === 'admin'" @click="goToDashboard"
                class="list-group-item list-group-item-action d-flex align-items-center">
                <i class="fas fa-tachometer-alt text-success me-3"></i>
                <span>Admin Dashboard</span>
              </a>

              <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                <i class="fas fa-cog text-success me-3"></i>
                <span>Account Settings</span>
              </a>
            </div>
          </div>
        </div>

        <!-- <div class="card shadow-sm mt-4">
          <div class="card-body text-center">
            <img src="../assets/imgs/user-access.png" class="img-fluid mb-3" alt="Profile Art">
            <h5>Welcome back, {{ user.name.split(' ')[0] }}!</h5>
            <p class="text-muted">Manage your profile to keep it updated and professional.</p>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data ()
  {
    return {
      user: {
        name: '',
        email: '',
        role: '',
        password: '',
        password_confirmation: ''
      },
      profile: {
        phone: '',
        address: '',
        bio: ''
      },
      profilePicture: null,
      resume: null
    };
  },
  filters: {
    capitalize: function ( value )
    {
      if ( !value ) return '';
      value = value.toString();
      return value.charAt( 0 ).toUpperCase() + value.slice( 1 );
    }
  },
  mounted ()
  {
    axios.get( '/api/profile' )
      .then( res =>
      {
        this.user = res.data.user;
        this.profile = res.data.profile;
        this.user.name = res.data.user.name;
      } )
      .catch( err =>
      {
        console.error( 'Error fetching profile:', err );
      } );
  },
  methods: {
    goToEdit ()
    {
      this.$router.push( '/edit-profile' );
    },
    goToJobs ()
    {
      this.$router.push( '/profile/jobs' );
    },
    goToDashboard ()
    {
      this.$router.push( '/dashboard' );
    }
  }
};
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

/* Minimal custom CSS - Most styling handled by Bootstrap */
.profile-picture-edit {
  position: absolute;
  bottom: 10px;
  right: 10px;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.card {
  border: none;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.list-group-item:hover {
  background-color: #f8f9fa;
  transform: translateX(5px);
}

.bg-light {
  background-color: #f8f9fa !important;
}

.text-success {
  color: #34A853 !important;
}

.btn-success {
  background-color: #34A853;
  border-color: #34A853;
}

.btn-success:hover {
  background-color: #2d9145;
  border-color: #2d9145;
}

.badge {
  font-weight: 500;
  letter-spacing: 0.5px;
}
</style>