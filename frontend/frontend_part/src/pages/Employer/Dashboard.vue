<template>
  <div class="container py-4 ">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>My Jobs</h2>
      <router-link to="/jobs/create" class="btn btn-primary">
        + Add Job
      </router-link>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Logo</th>
            <th>Title</th>
            <th>Description</th>
            <th>Responsibilities</th>
            <th>Qualifications</th>
            <th>Salary</th>
            <th>Location</th>
            <th>Job Type</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(job, index) in jobs" :key="job.id">
            <td>{{ index + 1 }}</td>
            <td>
              <img
                v-if="job.company_logo"
                :src="`http://127.0.0.1:8000/storage/${job.company_logo}`"
                alt="logo"
                class="img-thumbnail"
                style="width: 60px; height: 60px;"
              />
            </td>
            <td>{{ job.title }}</td>
            <td>{{ job.description }}</td>
            <td>{{ job.responsibilities || '-' }}</td>
            <td>{{ job.qualifications || '-' }}</td>
            <td>
              <span v-if="job.salary_from || job.salary_to">
                {{ job.salary_from }} - {{ job.salary_to }} EGP
              </span>
              <span v-else>-</span>
            </td>
            <td>{{ job.location }}</td>
            <td><span class="badge bg-secondary">{{ job.job_type }}</span></td>
            <td>{{ job.deadline }}</td>
            <td>
              <span :class="{
                'badge bg-success': job.status === 'approved',
                'badge bg-warning text-dark': job.status === 'pending',
                'badge bg-danger': job.status === 'rejected'
              }">
                {{ job.status }}
              </span>
            </td>
            <td>
              <router-link
                :to="`/jobs/${job.id}/edit`"
                class="text-primary me-2"
                title="Edit"
              >
                <i class="bi bi-pencil-square fs-5"></i>
              </router-link>
              <button
                class="btn btn-link text-danger p-0"
                @click="selectedJobId = job.id; showModal = true"
                title="Delete"
              >
                <i class="bi bi-trash fs-5"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

<div v-if="showModal" class="custom-modal-backdrop" @click.self="showModal = false">
  <transition name="fade-zoom">
    <div class="custom-modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" @click="showModal = false"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this job?</p>
      </div>
      <div class="modal-footer border-0">
        <button class="btn btn-secondary" @click="showModal = false">Cancel</button>
        <button class="btn btn-danger" @click="deleteJob(selectedJobId)">Yes, Delete</button>
      </div>
    </div>
  </transition>
</div>
  </div>
</template>

<script>
import axios from '@/api/axios';

export default {
  name: 'Dashboard',
  data() {
    return {
      jobs: [],
      loading: false,
      showModal: false,
      selectedJobId: null,
    };
  },
  methods: {
    async fetchJobs() {
      this.loading = true;
      try {
        const response = await axios.get('/jobs');
        this.jobs = response.data;
      } catch (error) {
        console.error('Error fetching data:', error);
      } finally {
        this.loading = false;
      }
    },
    async deleteJob(id) {
      try {
        await axios.delete(`/jobs/${id}`);
        this.jobs = this.jobs.filter((job) => job.id !== id);
        this.showModal = false;
      } catch (error) {
        console.error('Error deleting job:', error);
      }
    },
  },
  mounted() {
    this.fetchJobs();
  },
};
</script>


<style scoped>
.custom-modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1050;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.custom-modal-content {
  background: white;
  border-radius: 10px;
  padding: 20px;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.3);
}

.custom-modal-backdrop {
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to   { opacity: 1; }
}

</style>


