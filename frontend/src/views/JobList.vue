<template>
  <section class="job-section py-5">
    <div class="container-fluid">
      <div class="row">
        <!-- Left Sidebar (consistent with profile layout) -->
        <div class="col-lg-3 mb-4">
          <div class="card shadow-sm h-100">
            <div class="card-body text-center">
              <h3 class="mb-3">
                <i class="fas fa-briefcase text-success me-2"></i>Job Management
              </h3>
              <hr class="my-3">

              <div class="d-grid gap-2">
                <router-link to="/profile/jobs/create" class="btn btn-success">
                  <i class="fas fa-plus me-2"></i> Post New Job
                </router-link>

                <button @click="fetchJobs" class="btn btn-outline-dark">
                  <i class="fas fa-sync-alt me-2"></i> Refresh Jobs
                </button>
              </div>

              <hr class="my-4">

              <div class="job-stats">
                <div class="stat-item">
                  <i class="fas fa-briefcase text-success"></i>
                  <span>{{ jobs.length }} Active Jobs</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-9">
          <!-- Header -->
          <div class="card shadow-sm mb-4">
            <div class="card-body">
              <h4 class="mb-0">
                <i class="fas fa-list-alt text-success me-2"></i>My Posted Jobs
              </h4>
            </div>
          </div>

          <!-- Loading State -->
          <div v-if="loading" class="card shadow-sm">
            <div class="card-body text-center py-5">
              <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p class="mt-3 text-muted">Loading your job listings...</p>
            </div>
          </div>

          <!-- Error State -->
          <div v-else-if="error" class="card shadow-sm">
            <div class="card-body">
              <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ error }}
              </div>
              <button @click="fetchJobs" class="btn btn-success">
                <i class="fas fa-sync-alt me-2"></i> Try Again
              </button>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else-if="jobs.length === 0" class="card shadow-sm">
            <div class="card-body text-center py-5">
              <i class="fas fa-briefcase fa-4x text-muted mb-4"></i>
              <h4 class="text-muted">No jobs posted yet</h4>
              <p class="text-muted mb-4">Start by posting your first job opportunity</p>
              <router-link to="/profile/jobs/create" class="btn btn-success">
                <i class="fas fa-plus me-2"></i> Post a Job
              </router-link>
            </div>
          </div>

          <!-- Job Cards Grid -->
          <div v-else class="row g-4">
            <div v-for="job in jobs" :key="job.id" class="col-xl-4 col-md-6">
              <div class="card job-card h-100 shadow-sm">
                <div class="card-body d-flex flex-column p-4">
                  <!-- Header -->
                  <div class="d-flex align-items-center mb-3">

                    <h5 class="mb-0">{{ job.title }}</h5>
                  </div>

                  <!-- Description -->
                  <p class="text-muted small mb-3">{{ job.description }}</p>

                  <!-- Info Badges -->
                  <div class="mb-3 d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark border">
                      <i class="fas fa-map-marker-alt text-success me-1"></i>
                      {{ job.location || 'Remote' }}
                    </span>
                    <span v-if="job.salary_min || job.salary_max" class="badge bg-light text-dark border">
                      <i class="fas fa-dollar-sign text-success me-1"></i>
                      {{ job.salary_min || 'N/A' }} - {{ job.salary_max || 'N/A' }}
                    </span>
                  </div>

                  <!-- Skills -->
                  <div class="mb-4">
                    <h6 class="small fw-bold">Skills:</h6>
                    <div v-if="splitSkills(job.skills).length > 0" class="d-flex flex-wrap gap-2">
                      <span v-for="skill in splitSkills(job.skills)" :key="skill"
                        class="badge bg-success bg-opacity-10 text-light">
                        {{ skill }}
                      </span>
                    </div>
                    <p v-else class="text-muted small">No specific skills mentioned</p>
                  </div>

                  <!-- Action Buttons -->
                  <div class="mt-auto d-flex gap-2">
                    <button @click="goToEditJob(job.id)" class="btn btn-outline-success flex-grow-1">
                      <i class="fas fa-edit me-1"></i> Edit
                    </button>
                    <button @click="goToJobApplications(job.id)" class="btn btn-success flex-grow-1">
                      <i class="fas fa-users me-1"></i> View Apps
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from "axios";
import { useToast } from 'vue-toastification';
import api from '@/axios';

export default {
  data ()
  {
    return {
      jobs: [],
      loading: false,
      error: null
    };
  },
  mounted ()
  {
    this.fetchJobs();
  },
  methods: {
    fetchJobs ()
    {
      this.loading = true;
      this.error = null;

      api.get( "/jobs" )
        .then( ( response ) =>
        {
          if ( response.data && response.data.data )
          {
            this.jobs = response.data.data;
          } else
          {
            this.jobs = [];
            console.error( "Unexpected API response structure:", response.data );
          }
        } )
        .catch( ( error ) =>
        {
          this.error = "Failed to load jobs. Please try again later.";
          console.error( "Error fetching jobs:", error );
        } )
        .finally( () =>
        {
          this.loading = false;
        } );
    },
    goToEditJob ( jobId )
    {
      this.$router.push( `/profile/jobs/edit/${ jobId }` );
    },
    goToJobApplications ( jobId )
    {
      this.$router.push( `/jobs/${ jobId }/applicants` );
    },
    splitSkills ( skills )
    {
      if ( !skills ) return [];
      return skills.split( "," ).map( s => s.trim() ).filter( skill => skill !== '' );
    }
  }
};
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

/* Using exact colors from profile page */
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

.bg-success {
  background-color: #34A853 !important;
}

.bg-light {
  background-color: #f8f9fa !important;
}

/* Custom styles matching profile page */
.job-card {
  border-radius: 10px;
  border: 1px solid rgba(52, 168, 83, 0.1);
  transition: all 0.3s ease;
}

.job-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(52, 168, 83, 0.1);
}

.icon-circle {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.job-stats {
  margin-top: 1.5rem;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem;
  border-radius: 8px;
  background-color: rgba(52, 168, 83, 0.05);
}

.badge {
  font-weight: 500;
  padding: 0.5em 0.8em;
}

.btn-outline-success {
  color: #34A853;
  border-color: #34A853;
}

.btn-outline-success:hover {
  background-color: rgba(52, 168, 83, 0.1);
}

.card {
  border: none;
}

@media (max-width: 992px) {
  .col-lg-3 {
    margin-bottom: 2rem;
  }
}
</style>


