<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/axios';
import { useToast } from 'vue-toastification';

const toast = useToast();
const route = useRoute();
const router = useRouter();
const loading = ref( false );
const categories = ref( [] );

const isEditMode = computed( () => route.params.id !== undefined );

const job = ref( {
  title: '',
  description: '',
  category_id: '',
  skills: '',
  location: '',
  salary_min: null,
  salary_max: null,
} );

const errors = ref( {} );

onMounted( async () =>
{
  try
  {
    // Fetch categories
    const response = await api.get( '/jobs/create' );
    categories.value = response.data.data.categories;

    // If in edit mode, fetch job details
    if ( isEditMode.value )
    {
      const jobResponse = await api.get( `/jobs/${ route.params.id }/edit` );
      const jobData = jobResponse.data.data.job;
      job.value = {
        ...jobData,
        skills: Array.isArray( jobData.skills )
          ? jobData.skills.join( ', ' )
          : jobData.skills || ''
      };
    }
  } catch ( error )
  {
    toast.error( 'Failed to load data' );
    console.error( error );
  }
} );

const validateForm = () =>
{
  errors.value = {};
  let isValid = true;

  if ( !job.value.title )
  {
    errors.value.title = 'Title is required';
    isValid = false;
  }
  if ( !job.value.description )
  {
    errors.value.description = 'Description is required';
    isValid = false;
  }
  if ( !job.value.category_id )
  {
    errors.value.category_id = 'Category is required';
    isValid = false;
  }
  if ( !job.value.location )
  {
    errors.value.location = 'Location is required';
    isValid = false;
  }

  return isValid;
};

const handleSubmit = async () =>
{
  if ( !validateForm() )
  {
    toast.error( 'Please fill in all required fields' );
    return;
  }

  loading.value = true;
  errors.value = {};
  try
  {
    const jobData = {
      title: job.value.title,
      description: job.value.description,
      location: job.value.location,
      category_id: job.value.category_id,
      employer_id: 1,
      skills: job.value.skills || '',
    };

    if ( job.value.salary_min && job.value.salary_min !== '' )
    {
      const minValue = parseFloat( job.value.salary_min );
      jobData.salary_min = `${ minValue }`;
    }

    if ( job.value.salary_max && job.value.salary_max !== '' )
    {
      const maxValue = parseFloat( job.value.salary_max );
      jobData.salary_max = `${ maxValue }`;
    }

    if ( isEditMode.value )
    {
      await api.put( `/jobs/${ route.params.id }`, jobData );
      toast.success( 'Job updated successfully' );
    } else
    {
      await api.post( '/jobs', jobData );
      toast.success( 'Job created successfully' );
    }

    router.push( '/profile/jobs' );
  } catch ( error )
  {
    console.error( 'API Error:', error.response?.data );

    if ( error.response?.data?.errors )
    {
      errors.value = error.response.data.errors;
      const firstErrorField = Object.keys( errors.value )[ 0 ];
      const firstErrorMessage = errors.value[ firstErrorField ][ 0 ];
      toast.error( `${ firstErrorField }: ${ firstErrorMessage }` );
    } else
    {
      toast.error( error.response?.data?.message || 'An error occurred' );
    }
  } finally
  {
    loading.value = false;
  }
};
</script>

<template>
  <div class="job-form-container">
    <div class="container py-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white">
          <h2 class="mb-0">
            <i class="bi bi-briefcase me-2"></i>
            {{ isEditMode ? 'Edit Job Posting' : 'Create New Job' }}
          </h2>
        </div>

        <div class="card-body">
          <form @submit.prevent="handleSubmit" class="needs-validation">
            <!-- Job Title -->
            <div class="mb-4">
              <label class="form-label fw-bold text-success">Job Title *</label>
              <input type="text" class="form-control form-control-lg" :class="{ 'is-invalid': errors.title }"
                v-model="job.title" placeholder="e.g. Senior Web Developer" />
              <div class="invalid-feedback" v-if="errors.title">
                {{ Array.isArray(errors.title) ? errors.title[0] : errors.title }}
              </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
              <label class="form-label fw-bold text-success">Description *</label>
              <textarea class="form-control" :class="{ 'is-invalid': errors.description }" v-model="job.description"
                rows="5" placeholder="Detailed job description, responsibilities, and requirements"></textarea>
              <div class="invalid-feedback" v-if="errors.description">
                {{ Array.isArray(errors.description) ? errors.description[0] : errors.description }}
              </div>
            </div>

            <!-- Skills -->
            <div class="mb-4">
              <label class="form-label fw-bold text-success">Skills</label>
              <input type="text" class="form-control" :class="{ 'is-invalid': errors.skills }" v-model="job.skills"
                placeholder="JavaScript, PHP, Project Management (comma separated)" />
              <div class="form-text">List the key skills required for this position</div>
              <div class="invalid-feedback" v-if="errors.skills">
                {{ Array.isArray(errors.skills) ? errors.skills[0] : errors.skills }}
              </div>
            </div>

            <!-- Salary Range -->
            <div class="mb-4">
              <label class="form-label fw-bold text-success">Salary Range</label>
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" :class="{ 'is-invalid': errors.salary_min }"
                      v-model.number="job.salary_min" placeholder="Minimum" step="0.01" min="0" />
                    <div class="invalid-feedback" v-if="errors.salary_min">
                      {{ Array.isArray(errors.salary_min) ? errors.salary_min[0] : errors.salary_min }}
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" :class="{ 'is-invalid': errors.salary_max }"
                      v-model.number="job.salary_max" placeholder="Maximum" step="0.01" min="0" />
                    <div class="invalid-feedback" v-if="errors.salary_max">
                      {{ Array.isArray(errors.salary_max) ? errors.salary_max[0] : errors.salary_max }}
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Location -->
            <div class="mb-4">
              <label class="form-label fw-bold text-success">Location *</label>
              <input type="text" class="form-control" :class="{ 'is-invalid': errors.location }" v-model="job.location"
                placeholder="e.g. New York, NY or Remote" />
              <div class="invalid-feedback" v-if="errors.location">
                {{ Array.isArray(errors.location) ? errors.location[0] : errors.location }}
              </div>
            </div>

            <!-- Category -->
            <div class="mb-4">
              <label class="form-label fw-bold text-success">Category *</label>
              <select class="form-select" :class="{ 'is-invalid': errors.category_id }" v-model="job.category_id">
                <option value="">Select a category</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
              <div class="invalid-feedback" v-if="errors.category_id">
                {{ Array.isArray(errors.category_id) ? errors.category_id[0] : errors.category_id }}
              </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
              <router-link to="/profile/jobs" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Cancel
              </router-link>

              <button type="submit" class="btn btn-success px-4" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
                <i class="bi bi-save me-2"></i>
                {{ isEditMode ? 'Update Job' : 'Post Job' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.job-form-container {
  background-color: #f8f9fa;
  min-height: 100vh;
}

.card {
  border-radius: 12px;
  overflow: hidden;
}

.card-header {
  border-radius: 12px 12px 0 0 !important;
  padding: 1.5rem;
}

.form-control:focus,
.form-select:focus {
  border-color: #34A853;
  box-shadow: 0 0 0 0.25rem rgba(52, 168, 83, 0.25);
}

.btn-success {
  background-color: #34A853;
  border-color: #34A853;
}

.btn-success:hover {
  background-color: #2d9145;
  border-color: #2d9145;
}

.text-success {
  color: #34A853 !important;
}

.border-top {
  border-top: 1px solid rgba(0, 0, 0, 0.1) !important;
}

.invalid-feedback {
  color: #dc3545;
}

.form-text {
  color: #6c757d;
  font-size: 0.875rem;
}

@media (max-width: 768px) {
  .card-header {
    padding: 1rem;
  }

  h2 {
    font-size: 1.5rem;
  }
}
</style>