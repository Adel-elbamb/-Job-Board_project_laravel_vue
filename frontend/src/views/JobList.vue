<template>
    <div class="container mx-auto px-4 py-5">
        <h2 class="h3 fw-bold text-neutral mb-4">Available Jobs</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div v-for="job in jobs" :key="job.id" class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="card-title">{{ job.title }}</h3>
                        <p class="card-text text-muted">{{ job.location }} • {{ job.job_type }}</p>
                        <p class="card-text text-muted">Salary: ${{ job.salary_from }} - ${{ job.salary_to }}</p>
                        <p class="card-text">{{ job.description }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <router-link :to="{ name: 'ApplyForm', params: { jobId: job.id } }"
                            class="btn btn-secondary w-100">
                            Apply Now
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';
import { useToast } from 'vue-toastification';

const jobs = ref( [] );
const toast = useToast();

onMounted( async () =>
{
    try
    {
        const response = await api.get( '/jobs' );
        jobs.value = response.data.data;
    } catch ( error )
    {
        toast.error( 'Failed to load jobs' );
    }
} );
</script>