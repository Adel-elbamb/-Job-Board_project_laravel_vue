<template>
    <div class="container mx-auto px-4 py-5">
        <h2 class="h3 fw-bold text-neutral mb-4">My Applications</h2>
        <div v-if="!authStore.token" class="alert alert-warning">
            Please <router-link to="/login" class="text-primary">login</router-link> to view your applications.
        </div>
        <div v-else-if="applications.length === 0" class="alert alert-info">
            You have not applied to any jobs yet.
        </div>
        <div v-else class="row row-cols-1 row-cols-md-2 g-4">
            <div v-for="application in applications" :key="application.id" class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="card-title">{{ application.job.title }}</h3>
                        <p class="card-text text-muted">Applied on: {{ new
                            Date(application.created_at).toLocaleDateString() }}</p>
                        <p class="card-text">Contact Info: {{ application.contact_info || 'N/A' }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <button @click="cancelApplication(application.id)" class="btn btn-outline-danger w-100">
                            Cancel Application
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';
import { useAuthStore } from '../stores/auth';
import { toast } from 'vue3-toastify';

const applications = ref( [] );
const authStore = useAuthStore();

onMounted( async () =>
{
    if ( authStore.token )
    {
        await fetchApplications();
    }
} );

const fetchApplications = async () =>
{
    try
    {
        const response = await api.get( '/applications/my' );
        applications.value = response.data.data;
    } catch ( error )
    {
        const errorMessage = error.response?.data?.message || 'Failed to load applications';
        toast.error( errorMessage );
        console.error( 'Fetch applications error:', error.response?.data || error.message );
    }
};

const cancelApplication = async ( applicationId ) =>
{
    try
    {
        const response = await api.delete( `/applications/${ applicationId }` );
        toast.success( 'Application canceled' );
        await fetchApplications();
    } catch ( error )
    {
        const errorMessage = error.response?.data?.message || 'Failed to cancel application';
        toast.error( errorMessage );
    }
};
</script>