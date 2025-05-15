<template>
  <section class="job-section bg-light" aria-labelledby="job-section-title">
    <div class="container-fluid">
      <!-- Header -->
      <header class="header py-4 text-center text-white" style="background-color: #2E7D32;">
        <div class="container">
          <h1 id="job-section-title" class="fw-bold mb-2">
            Find Your Dream Job
          </h1>
          <p class="mb-3">Browse thousands of job listings to find your perfect match</p>
          <div class="input-group mb-3 w-50 mx-auto">
            <input
              v-model="filters.titledesc"
              type="text"
              class="form-control"
              placeholder="Job title, keywords, or company"
              aria-label="Search for jobs"
            />
            <button @click="search(1)" class="btn btn-success">Search</button>
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <div class="container py-4">
        <div class="row">
          <!-- Filters Column -->
          <aside class="col-lg-3 col-md-4 mb-4">
            <div class="card p-3 shadow-sm">
              <h5 class="card-title mb-3 text-success">Filters</h5>
              <form @submit.prevent="search(1)">
                <div class="mb-3">
                  <label class="form-label text-muted small">Location</label>
                  <select v-model="filters.location" class="form-select">
                    <option value="">Any location</option>
                    <option value="Remote">Remote</option>
                    <option value="New York">New York</option>
                    <option value="London">London</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label text-muted small">Job Category</label>
                  <select v-model="filters.category" class="form-select">
                    <option value="">All Categories</option>
                    <option value="Frontend">Frontend</option>
                    <option value="Backend">Backend</option>
                    <option value="DevOps">DevOps</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label text-muted small">Salary Range</label>
                  <select v-model="filters.salary_min" class="form-select mb-2">
                    <option value="">Min Salary</option>
                    <option value="30000">$30,000</option>
                    <option value="50000">$50,000</option>
                    <option value="70000">$70,000</option>
                  </select>
                  <select v-model="filters.salary_max" class="form-select">
                    <option value="">Max Salary</option>
                    <option value="50000">$50,000</option>
                    <option value="70000">$70,000</option>
                    <option value="90000">$90,000</option>
                  </select>
                </div>
                <div class="d-flex gap-2">
                  <button
                    type="reset"
                    @click="resetFilters"
                    class="btn btn-outline-success w-50"
                  >
                    Reset
                  </button>
                  <button
                    type="submit"
                    class="btn btn-success w-50"
                  >
                    Search
                  </button>
                </div>
              </form>
              <p class="text-success mt-3 mb-0">
                <span class="fw-bold">{{ pagination.total }}</span> Jobs Found
              </p>
            </div>
          </aside>

          <!-- Jobs Column -->
          <main class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <div></div>
              <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">Sort by:</span>
                <select v-model="sortOption" class="form-select form-select-sm" @change="search(1)">
                  <option value="relevance">Most Relevant</option>
                  <option value="date">Newest First</option>
                  <option value="salary">Highest Salary</option>
                </select>
              </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="text-center py-5">
              <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p class="mt-3 text-muted">Fetching job opportunities...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="alert alert-danger text-center" role="alert">
              <i class="bi bi-exclamation-circle-fill me-2"></i>
              {{ error }}
              <button @click="fetchJobs" class="btn btn-link">Try Again</button>
            </div>

            <!-- Empty State -->
            <div v-else-if="!jobs.length" class="text-center py-5">
              <i class="bi bi-briefcase-fill display-1 text-muted"></i>
              <h3 class="mt-3 text-muted">No Jobs Found</h3>
              <p class="text-muted">Try adjusting your search filters or check back later.</p>
              <button @click="resetFilters" class="btn btn-outline-success mt-3">
                Reset Filters
              </button>
            </div>

            <!-- Job Cards -->
            <div v-else class="row g-4">
              <div
                v-for="(job) in jobs"
                :key="job.id"
                class="col-12"
              >
                <article class="card p-3 shadow-sm">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0 fw-semibold text-dark">{{ job.title || 'Untitled Job' }}</h5>
                    <span v-if="job.isFeatured" class="badge bg-success text-white">Featured</span>
                  </div>
                  <div class="d-flex justify-content-between text-muted small mb-2">
                    <span>{{ job.company || 'Company Name' }} • {{ job.location || 'Remote' }}</span>
                    <span v-if="job.salary_min || job.salary_max" class="text-dark">
                      {{ formatSalary(job.salary_min, job.salary_max) }}
                    </span>
                  </div>
                  <p class="text-muted mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ job.description || 'No description available.' }}
                  </p>
                  <div class="mb-3">
                    <strong class="text-dark">Skills:</strong>
                    <div v-if="splitSkills(job.skills).length" class="d-flex flex-wrap gap-2 mt-1">
                      <span
                        v-for="skill in splitSkills(job.skills)"
                        :key="skill"
                        class="badge bg-light text-dark border"
                      >
                        {{ skill }}
                      </span>
                    </div>
                    <p v-else class="text-muted small mt-1">No specific skills required.</p>
                  </div>
                  <div class="d-flex justify-content-between align-items-center text-muted small">
                    <span>{{ job.category || 'Frontend' }}</span>
                    <span>Posted {{ job.posted_date || '2 days ago' }}</span>
                  </div>
                  <button
                    @click="goToApplyPage(job.id)"
                    class="btn btn-success w-100 mt-3"
                    :aria-label="`Apply for ${job.title || 'this job'}`"
                  >
                    Apply Now
                  </button>
                </article>
              </div>
            </div>

            <!-- Pagination -->
            <div v-if="jobs.length > 0" class="d-flex justify-content-center mt-5">
              <nav aria-label="Job search pagination">
                <ul class="pagination">
                  <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                    <button class="page-link" @click="search(pagination.current_page - 1)">
                      <i class="bi bi-chevron-left"></i>
                    </button>
                  </li>
                  <li
                    v-for="page in visiblePages"
                    :key="page"
                    class="page-item"
                    :class="{ active: pagination.current_page === page }"
                  >
                    <button class="page-link" @click="search(page)">
                      {{ page }}
                    </button>
                  </li>
                  <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
                    <button class="page-link" @click="search(pagination.current_page + 1)">
                      <i class="bi bi-chevron-right"></i>
                    </button>
                  </li>
                </ul>
              </nav>
            </div>
          </main>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useToast } from 'vue-toastification';
import { useRouter } from 'vue-router';
import api from '@/axios';

const toast = useToast();
const router = useRouter();

const jobs = ref([]);
const loading = ref(false);
const error = ref(null);
const filters = ref({
  titledesc: '',
  location: '',
  category: '',
  salary_min: null,
  salary_max: null,
  from_date: '',
});
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 12,
  total: 0,
});
const sortOption = ref('relevance');

const visiblePages = computed(() => {
  const range = 3;
  const start = Math.max(2, pagination.value.current_page - range);
  const end = Math.min(pagination.value.last_page - 1, pagination.value.current_page + range);

  let pages = [1];
  if (start > 2) pages.push('...');
  for (let i = start; i <= end; i++) pages.push(i);
  if (end < pagination.value.last_page - 1) pages.push('...');
  if (pagination.value.last_page > 1) pages.push(pagination.value.last_page);
  return pages;
});

const fetchJobs = async (page = 1) => {
  try {
    loading.value = true;
    error.value = null;

    const params = {
      page,
      limit: pagination.value.per_page,
      sort: sortOption.value,
      ...filters.value,
    };

    Object.keys(params).forEach(key => {
      if (params[key] === '' || params[key] === null) delete params[key];
    });

    const response = await api.get('/jobs', { params });

    jobs.value = Array.isArray(response.data?.data) ? response.data.data : [];

    if (response.data?.meta) {
      pagination.value = {
        current_page: response.data.meta.current_page,
        last_page: response.data.meta.last_page,
        per_page: response.data.meta.per_page,
        total: response.data.meta.total,
      };
    }

    if (!jobs.value.length) {
      toast.info('No jobs found matching your criteria.');
    }
  } catch (err) {
    error.value = 'Unable to load jobs. Please try again.';
    toast.error(error.value);
    console.error('Error fetching jobs:', err);
  } finally {
    loading.value = false;
  }
};

const search = async (page = 1) => {
  pagination.value.current_page = page;
  await fetchJobs(page);
};

const resetFilters = () => {
  filters.value = {
    titledesc: '',
    location: '',
    category: '',
    salary_min: null,
    salary_max: null,
    from_date: '',
  };
  sortOption.value = 'relevance';
  search(1);
};

const goToApplyPage = (jobId) => {
  if (!jobId) {
    toast.error('Invalid job ID');
    return;
  }
  router.push({ name: 'applyJob', params: { jobId } });
};

const splitSkills = (skills) => {
  return skills
    ? skills.split(',').map(s => s.trim()).filter(skill => skill)
    : [];
};

const formatSalary = (min, max) => {
  if (!min && !max) return 'N/A';
  const formatCurrency = (amount) =>
    new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      maximumFractionDigits: 0,
    }).format(amount);
  return `${min ? formatCurrency(min) : ''} - ${max ? formatCurrency(max) : ''}`.trim() || 'N/A';
};

onMounted(() => {
  fetchJobs();
});
</script>

<style scoped>
.header {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.job-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.badge.bg-light {
  border: 1px solid #E5E7EB;
}

.btn-success:hover {
  background: #1B5E20;
}

.btn-outline-success:hover {
  background: #E8F5E9;
  color: #1B5E20;
}

.pagination {
  --bs-pagination-color: #2E7D32;
  --bs-pagination-active-bg: #2E7D32;
  --bs-pagination-active-border-color: #2E7D32;
  --bs-pagination-hover-color: #1B5E20;
}

@media (max-width: 768px) {
  .input-group {
    flex-direction: column;
  }
  .input-group .form-control {
    border-radius: 6px 6px 0 0;
    border-bottom: none;
  }
  .input-group .btn-success {
    border-radius: 0 0 6px 6px;
    width: 100%;
  }
}
</style>
