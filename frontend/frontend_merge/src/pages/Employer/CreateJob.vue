<template>
  <div class="container py-5">
    <div class="card shadow-lg">
      <div class="card-header bg-primary text-white">
        <h3 class="mb-0">Add New Job</h3>
      </div>

      <div class="card-body">
        <form @submit.prevent="submitJob">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input v-model="form.title" type="text" class="form-control" required />
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea v-model="form.description" class="form-control" rows="3" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Responsibilities</label>
            <textarea v-model="form.responsibilities" class="form-control" rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Qualifications</label>
            <input v-model="form.qualifications" type="text" class="form-control" />
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Salary From</label>
              <input v-model="form.salary_from" type="number" class="form-control" />
            </div>
            <div class="col-md-6">
              <label class="form-label">Salary To</label>
              <input v-model="form.salary_to" type="number" class="form-control" />
            </div>
            <div v-if="errors.salary" class="text-danger mt-1">{{ errors.salary }}</div>
          </div>

          <div class="mb-3">
            <label class="form-label">Location</label>
            <input v-model="form.location" type="text" class="form-control" />
          </div>

          <div class="mb-3">
            <label class="form-label">Job Type</label>
            <select v-model="form.job_type" class="form-select">
              <option value="remote">Remote</option>
              <option value="on-site">On-site</option>
              <option value="hybrid">Hybrid</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Deadline</label>
            <input v-model="form.deadline" type="date" class="form-control" />
            <div v-if="errors.deadline" class="text-danger mt-1">{{ errors.deadline }}</div>

          </div>

          <div class="mb-3">
            <label class="form-label">Company Logo</label>
            <input type="file" @change="handleLogoUpload" class="form-control" accept="image/*" />
            <div v-if="previewLogo" class="mt-3">
              <img :src="previewLogo" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;" />
            </div>
            <div v-if="errors.image" class="text-danger mt-1">{{ errors.image }}</div>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Submit Job</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>


<script>
import axios from '@/api/axios';

export default {
  name: 'CreateJob',
  data() {
    return {
      form: {
        title: '',
        description: '',
        responsibilities: '',
        qualifications: '',
        salary_from: '',
        salary_to: '',
        location: '',
        job_type: 'remote',
        deadline: '',
        status: 'pending',
      },
      company_logo: null,
      previewLogo: null,
      errors: {
          salary: null,
          deadline: null,
          image: null
      },
    };
  },
  methods: {
  handleLogoUpload(event) {
  const file = event.target.files[0];
  this.errors.image = null;
  this.previewLogo = null;

  if (!file) {
    this.company_logo = null;
    return;
  }

  const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];

  if (!allowedTypes.includes(file.type)) {
    this.errors.image = 'Please upload a valid image file (jpeg, png, jpg, gif, svg).';
    this.company_logo = null;
    return;
  }

  this.company_logo = file;
  this.previewLogo = URL.createObjectURL(file);
}
,
    validateForm() {
  this.errors = {};

  if (this.form.salary_from && this.form.salary_to && Number(this.form.salary_from) > Number(this.form.salary_to)) {
    this.errors.salary = 'Salary From must be less than or equal to Salary To';
  }

  const today = new Date().toISOString().split('T')[0];
  if (this.form.deadline && this.form.deadline < today) {
    this.errors.deadline = 'Deadline must be a future date';
  }

  

  if (this.company_logo) {
  const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
  if (!allowedTypes.includes(this.company_logo.type)) {
    this.errors.image = 'Company logo must be a valid image (jpeg, png, jpg, gif, svg)';
  }
  
}
return Object.keys(this.errors).length === 0;

},




    async submitJob() {
       if (!this.validateForm()) {
                return; 
        }
      try {
        const formData = new FormData();
        for (const key in this.form) {
          formData.append(key, this.form[key]);
        }
        if (this.company_logo) {
          formData.append('company_logo', this.company_logo);
        }

        const response = await axios.post('/jobs', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            // 'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
          },
        });

        this.$router.push('/dashboard/employerdashboard');
      } catch (error) {
        console.error('failed in adding job:', error);
      }
    },
  },
};
</script>

<style scoped>
.input {
  width: 100%;
  border: 1px solid #ddd;
  padding: 0.5rem;
  border-radius: 0.375rem;
}
</style>
