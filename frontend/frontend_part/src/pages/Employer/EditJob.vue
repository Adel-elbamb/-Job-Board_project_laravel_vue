<template>
  <div class="container py-5">
    <div class="card shadow-lg">
      <div class="card-header bg-primary text-white">
        <h3 class="mb-0">Edit Job</h3>
      </div>
      <div class="card-body">

       <div class="d-flex justify-content-start mb-3">
        <button class="btn btn-outline-primary" @click="goToDashboard">
            <i class="bi bi-arrow-left-circle me-2"></i> Go to Dashboard
        </button>
        </div>

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
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Update Job</button>
          </div>
        </form>
      </div>
    </div>
   <transition name="fade-zoom">
      <div v-if="showModal" class="custom-modal-backdrop" @click.self="showModal = false">
        <div class="custom-modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Update Successful</h5>
            <button type="button" class="btn-close" @click="showModal = false"></button>
          </div>
          <div class="modal-body">
            <p>Your job has been updated successfully!</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="showModal = false">Close</button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import axios from '@/api/axios';

export default {
  name: 'EditJob',
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
        loading: true,
      },
      company_logo: null,
      previewLogo: null,
      showModal: false,
       errors: {},
    };
  },
  methods: {
   goToDashboard() {
  this.$router.push('/dashboard');
},

   async fetchJob() {
  try {
    const res = await axios.get(`/jobs/${this.$route.params.id}`);
    console.log('Fetched job:', res.data);
    this.form = {
      ...res.data,
      job_type: res.data.job_type || 'remote',
    };
    delete this.form.company_logo; 
    if (res.data.company_logo) {
      this.previewLogo = `http://127.0.0.1:8000/storage/${res.data.company_logo}`;
    }
  } catch (err) {
    console.error('Error loading job:', err);
  } finally {
    this.loading = false; 
  }
},

handleLogoUpload(e) {
  const file = e.target.files[0];
  if (!file) return;

  const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
  if (!allowedTypes.includes(file.type)) {
    alert('Please upload a valid image file (jpeg, png, jpg, gif, svg).');
    return;
  }

  this.company_logo = file;
  this.previewLogo = URL.createObjectURL(file);
},

validateForm() {
  this.errors = {}; 
  if(this.form.salary_from==0 ||this.form.salary_from==0 )
  {
    this.errors.salary = 'Salary must not be null';
  }
  if (this.form.salary_from && this.form.salary_to && Number(this.form.salary_from) > Number(this.form.salary_to)) {
    this.errors.salary = 'Salary From must be less than or equal to Salary To';
  }

  const today = new Date().toISOString().split('T')[0];
  if (this.form.deadline && this.form.deadline < today) {
    this.errors.deadline = 'Deadline must be a future date';
  }

  return Object.keys(this.errors).length === 0;
},

    async submitJob() {
    if (!this.validateForm()) {
                return; 
        }try{

        const formData = new FormData();
        
        for (const key in this.form) {
        if (key !== 'company_logo' && this.form[key] !== null && this.form[key] !== undefined && this.form[key] !== '') {
        formData.append(key, this.form[key]);
      }

        }
        if (this.company_logo) {
          formData.append('company_logo', this.company_logo);
        }

const res = await axios.post(`/jobs/${this.$route.params.id}?_method=PUT`, formData, {
  headers: { 'Content-Type': 'multipart/form-data' },
});

            console.log('Job updated successfully:', res.data);
    

        this.showModal = true;
        

      } catch (err) {
        console.error('Error updating job:', err);
      }
    },
  },
  mounted() {
    this.fetchJob();
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

