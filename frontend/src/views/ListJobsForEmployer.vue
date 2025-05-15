<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'vue-toastification';
import api from '@/axios';
import JobCard from '@/components/JobCard.vue';

const jobs = ref( [] );
const loading = ref( true );
const toast = useToast();
const currentPage = ref( 1 );
const totalPages = ref( 1 );
const itemsPerPage = 10;

const fetchJobs = async () =>
{
  try
  {
    loading.value = true;
    const response = await api.get( '/jobs', {
      params: {
        page: currentPage.value,
        per_page: itemsPerPage
      }
    } );

    if ( response.data && response.data.data )
    {
      jobs.value = response.data.data;
      totalPages.value = response.data.meta?.last_page || 1;
    } else
    {
      jobs.value = [];
      toast.warning( 'Received unexpected data format from server' );
    }
  } catch ( error )
  {
    toast.error( 'Failed to fetch jobs' );
  } finally
  {
    loading.value = false;
  }
};

const handleJobDeleted = ( jobId ) =>
{
  jobs.value = jobs.value.filter( job => job.id !== jobId );
  toast.success( 'Job deleted successfully' );
};

const changePage = ( page ) =>
{
  currentPage.value = page;
  fetchJobs();
};

onMounted( fetchJobs );
</script>

<template>
  <div class="jobs-container">
    <div class="jobs-header">
      <h2 class="jobs-title">
        <i class="fas fa-briefcase me-2 text-success"></i>My Posted Jobs
      </h2>

      <router-link to="/profile/jobs/create" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Post New Job
      </router-link>
    </div>

    <div class="jobs-content">
      <div v-if="loading" class="loading-state">
        <div class="spinner-border text-success" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3">Loading your job listings...</p>
      </div>

      <div v-else-if="jobs.length === 0" class="empty-state">
        <div class="empty-icon">
          <i class="fas fa-briefcase fa-3x"></i>
        </div>
        <h4>No jobs posted yet</h4>
        <p class="mb-4">Start by posting your first job opportunity</p>
        <router-link to="/profile/jobs/create" class="btn btn-success">
          <i class="fas fa-plus me-2"></i>Post a Job
        </router-link>
      </div>

      <div v-else class="jobs-grid">
        <JobCard v-for="job in jobs" :key="job.id" :job="job" @job-deleted="handleJobDeleted" class="job-card" />

        <div v-if="totalPages > 1" class="pagination-container">
          <nav aria-label="Jobs pagination">
            <ul class="pagination">
              <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <button class="page-link" @click="changePage(currentPage - 1)">
                  <i class="fas fa-chevron-left"></i> Previous
                </button>
              </li>

              <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: currentPage === page }">
                <button class="page-link" @click="changePage(page)">
                  {{ page }}
                </button>
              </li>

              <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                <button class="page-link" @click="changePage(currentPage + 1)">
                  Next <i class="fas fa-chevron-right"></i>
                </button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

.jobs-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.jobs-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.jobs-title {
  font-weight: 700;
  color: #2d3748;
  margin: 0;
  display: flex;
  align-items: center;
}

.btn-success {
  background-color: #34A853;
  border-color: #34A853;
  padding: 0.5rem 1.25rem;
  font-weight: 500;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
}

.btn-success:hover {
  background-color: #2d9145;
  border-color: #2d9145;
  transform: translateY(-2px);
}

.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 300px;
  color: #4a5568;
}

.empty-state {
  text-align: center;
  padding: 3rem;
  background-color: #f8f9fa;
  border-radius: 12px;
  margin: 2rem 0;
}

.empty-icon {
  color: #34A853;
  margin-bottom: 1.5rem;
}

.jobs-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
  margin: 2rem 0;
}

.job-card {
  transition: all 0.3s ease;
  border-radius: 10px;
  overflow: hidden;
}

.job-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.pagination-container {
  display: flex;
  justify-content: center;
  margin-top: 3rem;
}

.page-item.active .page-link {
  background-color: #34A853;
  border-color: #34A853;
}

.page-link {
  color: #34A853;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
}

.page-link:hover {
  color: #2d9145;
}

@media (max-width: 768px) {
  .jobs-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .jobs-grid {
    grid-template-columns: 1fr;
  }
}
</style>