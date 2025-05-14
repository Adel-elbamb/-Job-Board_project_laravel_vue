<template>
    <div class="container mx-auto px-4 py-5">
        <h2 class="h3 fw-bold text-neutral mb-4">Apply for Job</h2>
        <div v-if="!authStore.token" class="alert alert-warning">
            Please <router-link to="/login" class="text-primary">login</router-link> to submit an application.
        </div>
        <form v-else @submit.prevent="submitApplication" class="col-md-8">
            <div class="mb-3">
                <label for="job_id" class="form-label fw-medium">Job</label>
                <select v-model="form.job_id" id="job_id" class="form-select" required>
                    <option value="" disabled>Select a job</option>
                    <option v-for="job in jobs" :key="job.id" :value="job.id">{{ job.title }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="contact_info" class="form-label fw-medium">Contact Info (Optional)</label>
                <input v-model="form.contact_info" id="contact_info" type="text" class="form-control"
                    placeholder="e.g., Phone: 123-456-7890" />
            </div>
            <div class="mb-3">
                <label for="resume" class="form-label fw-medium">Resume (Optional)</label>
                <input type="file" id="resume" class="form-control" @change="handleFileUpload" />
            </div>
            <button type="submit" class="btn btn-secondary">Submit Application</button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '../api';
import { useAuthStore } from '../stores/auth';
import { toast } from 'vue3-toastify';

const form = ref( { job_id: '', contact_info: '', resume: null } );
const jobs = ref( [] );
const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

onMounted( async () =>
{
    try
    {
        const response = await api.get( '/jobs' );
        jobs.value = response.data.data;
        if ( route.params.jobId )
        {
            form.value.job_id = route.params.jobId;
        }
    } catch ( error )
    {
        toast.error( 'Failed to load jobs' );
    }
} );

const handleFileUpload = ( event ) =>
{
    form.value.resume = event.target.files[ 0 ];
};

const submitApplication = async () =>
{
    try
    {
        const formData = new FormData();
        formData.append( 'job_id', form.value.job_id );
        if ( form.value.contact_info ) formData.append( 'contact_info', form.value.contact_info );
        if ( form.value.resume ) formData.append( 'resume', form.value.resume );

        const response = await api.post( '/applications', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        } );
        toast.success( 'Application submitted' );
        router.push( '/dashboard' );
    } catch ( error )
    {
        const errorMessage = error.response?.data?.message || 'Failed to submit application';
        toast.error( errorMessage );
        console.error( 'Application error:', error.response?.data || error.message );
    }
};
</script>