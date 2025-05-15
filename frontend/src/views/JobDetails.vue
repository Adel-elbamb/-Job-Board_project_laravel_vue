<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/axios';
import { useToast } from 'vue-toastification';

const toast = useToast();
const route = useRoute();
const router = useRouter();
const job = ref( null );
const category = ref( '' );
const loading = ref( true );
const user = ref( null );
const isAllowedToEdit = ref( false );

const formatDate = ( dateString ) =>
{
  if ( !dateString ) return 'N/A';
  const date = new Date( dateString );
  return new Intl.DateTimeFormat( 'en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  } ).format( date );
};

const checkUserPermissions = async () =>
{
  try
  {
    const token = localStorage.getItem( 'token' );
    if ( !token )
    {
      isAllowedToEdit.value = false;
      return;
    }

    const response = await api.get( '/user' );
    user.value = response.data;

    if ( user.value )
    {
      const role = user.value.role?.toLowerCase();
      isAllowedToEdit.value = role === 'employer' || role === 'admin';
    }
  } catch ( error )
  {
    console.error( 'Error checking user permissions:', error );
    isAllowedToEdit.value = false;
  }
};

onMounted( async () =>
{
  try
  {
    const jobId = route.params.id;

    const [ jobResponse ] = await Promise.all( [
      api.get( `/jobs/${ jobId }` ),
      checkUserPermissions()
    ] );

    job.value = jobResponse.data.data.job;
    category.value = jobResponse.data.data.category;
  } catch ( error )
  {
    console.error( 'Error fetching job details:', error );
    toast.error( 'Failed to load job details' );
  } finally
  {
    loading.value = false;
  }
} );

const navigateToEdit = () =>
{
  router.push( `/profile/jobs/edit/${ job.value.id }` );
};

const goBack = () =>
{
  router.push( '/profile/jobs' );
};
</script>

<template>
  <section class="job-details-section py-5">
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
                <router-link to="/profile/jobs" class="btn btn-outline-dark">
                  <i class="fas fa-arrow-left me-2"></i> Back to Jobs
                </router-link>

                <button v-if="isAllowedToEdit" @click="navigateToEdit" class="btn btn-success">
                  <i class="fas fa-edit me-2"></i> Edit Job
                </button>
              </div>

              <hr class="my-4">

              <div class="job-stats">
                <div class="stat-item">
                  <i class="fas fa-calendar-alt text-success"></i>
                  <span v-if="job">{{ formatDate(job.created_at) }}</span>
                  <span v-else>Loading date...</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-9">
          <!-- Loading State -->
          <div v-if="loading" class="card shadow-sm">
            <div class="card-body text-center py-5">
              <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p class="mt-3 text-muted">Loading job details...</p>
            </div>
          </div>

          <!-- Error/Not Found State -->
          <div v-else-if="!job" class="card shadow-sm">
            <div class="card-body text-center py-5">
              <i class="fas fa-exclamation-triangle fa-4x text-muted mb-4"></i>
              <h4 class="text-muted">Job not found</h4>
              <p class="text-muted mb-4">The job you're looking for does not exist or has been removed.</p>
              <router-link to="/profile/jobs" class="btn btn-success">
                <i class="fas fa-arrow-left me-2"></i> Back to Jobs
              </router-link>
            </div>
          </div>

          <!-- Job Details Content -->
          <div v-else>
            <!-- Header Card -->
            <div class="card shadow-sm mb-4">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h2 class="mb-0">
                    <i class="fas fa-file-alt text-success me-2"></i>{{ job.title }}
                  </h2>
                  <span :class="{
                    'badge bg-success': job.status === 'accepted',
                    'badge bg-warning text-dark': job.status === 'pending',
                    'badge bg-danger': job.status === 'rejected',
                    'badge bg-secondary': !job.status
                  }">
                    {{ job.status || 'Unknown' }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Job Details Card -->
            <div class="card shadow-sm mb-4">
              <div class="card-body">
                <div class="row">
                  <!-- Left Column -->
                  <div class="col-md-6">
                    <h5 class="border-bottom pb-2 mb-3">
                      <i class="fas fa-info-circle text-success me-2"></i>Job Information
                    </h5>

                    <div class="detail-item mb-3">
                      <div class="detail-label">
                        <i class="fas fa-map-marker-alt text-success me-2"></i>Location
                      </div>
                      <div class="detail-value">{{ job.location || 'Not specified' }}</div>
                    </div>

                    <div class="detail-item mb-3">
                      <div class="detail-label">
                        <i class="fas fa-tag text-success me-2"></i>Category
                      </div>
                      <div class="detail-value">{{ category || 'Uncategorized' }}</div>
                    </div>

                    <div class="detail-item mb-3">
                      <div class="detail-label">
                        <i class="fas fa-dollar-sign text-success me-2"></i>Salary Range
                      </div>
                      <div class="detail-value">
                        <span v-if="job.salary_min || job.salary_max">
                          {{ job.salary_min ? `$${job.salary_min.toLocaleString()}` : 'Not specified' }}
                          {{ job.salary_max ? ` - $${job.salary_max.toLocaleString()}` : '' }}
                        </span>
                        <span v-else>Not specified</span>
                      </div>
                    </div>

                    <div class="detail-item mb-3">
                      <div class="detail-label">
                        <i class="fas fa-calendar-plus text-success me-2"></i>Posted Date
                      </div>
                      <div class="detail-value">{{ formatDate(job.created_at) }}</div>
                    </div>

                    <div class="detail-item">
                      <div class="detail-label">
                        <i class="fas fa-calendar-check text-success me-2"></i>Last Updated
                      </div>
                      <div class="detail-value">{{ formatDate(job.updated_at) }}</div>
                    </div>
                  </div>

                  <!-- Right Column -->
                  <div class="col-md-6">
                    <h5 class="border-bottom pb-2 mb-3">
                      <i class="fas fa-tools text-success me-2"></i>Skills Required
                    </h5>

                    <div v-if="job.skills && job.skills.length > 0" class="skills-container mb-4">
                      <span
                        v-for="(skill, index) in typeof job.skills === 'string' ? job.skills.split(',') : job.skills"
                        :key="index" class="skill-badge">
                        {{ skill.trim() }}
                      </span>
                    </div>
                    <div v-else class="text-muted">
                      <i class="fas fa-info-circle me-2"></i>No specific skills mentioned
                    </div>

                    <div v-if="job.requirements" class="mt-4">
                      <h5 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-clipboard-check text-success me-2"></i>Requirements
                      </h5>
                      <ul class="requirements-list">
                        <li v-for="(req, index) in job.requirements.split('\n')" :key="index">
                          <i class="fas fa-check-circle text-success me-2"></i>{{ req }}
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Description Card -->
            <div class="card shadow-sm">
              <div class="card-body">
                <h5 class="border-bottom pb-2 mb-3">
                  <i class="fas fa-align-left text-success me-2"></i>Job Description
                </h5>
                <div class="job-description" style="white-space: pre-line;">{{ job.description }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

/* Color scheme matching the profile page */
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

/* Custom styles */
.job-details-section {
  background-color: #f8f9fa;
}

.card {
  border: none;
  border-radius: 10px;
}

.detail-item {
  display: flex;
  margin-bottom: 1rem;
}

.detail-label {
  font-weight: 600;
  width: 150px;
  color: #495057;
}

.detail-value {
  flex: 1;
  color: #212529;
}

.skills-container {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.skill-badge {
  background-color: rgba(52, 168, 83, 0.1);
  color: #34A853;
  padding: 0.35rem 0.75rem;
  border-radius: 50px;
  font-size: 0.85rem;
  font-weight: 500;
  border: 1px solid rgba(52, 168, 83, 0.2);
}

.requirements-list {
  list-style-type: none;
  padding-left: 0;
}

.requirements-list li {
  padding: 0.25rem 0;
}

.job-description {
  line-height: 1.8;
  color: #495057;
}

.badge {
  font-weight: 500;
  padding: 0.5em 0.8em;
  text-transform: capitalize;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem;
  border-radius: 8px;
  background-color: rgba(52, 168, 83, 0.05);
}

@media (max-width: 768px) {
  .detail-item {
    flex-direction: column;
  }

  .detail-label {
    width: 100%;
    margin-bottom: 0.25rem;
  }
}
</style>