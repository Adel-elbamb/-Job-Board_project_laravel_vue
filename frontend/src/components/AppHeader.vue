<template>
    <header class="bg-white shadow py-3">
        <nav class="container mx-auto px-4 d-flex justify-content-between align-items-center">
            <h1 class="h4 fw-bold text-primary">Jobpilot</h1>
            <div class="d-flex gap-3">
                <router-link to="/" class="text-decoration-none text-neutral">Jobs</router-link>
                <router-link v-if="isCandidate" to="/dashboard"
                    class="text-decoration-none text-neutral">Dashboard</router-link>
                <button v-if="isLoggedIn" @click="authStore.logout"
                    class="btn btn-outline-danger btn-sm">Logout</button>
                <router-link v-else to="/login" class="text-decoration-none text-neutral">Login</router-link>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { computed } from 'vue';
import { useAuthStore } from '../stores/auth';

const authStore = useAuthStore();

// Computed properties to ensure boolean values for v-if
const isLoggedIn = computed( () => authStore.token !== null && authStore.token !== '' );
const isCandidate = computed( () => authStore.user?.role === 'candidate' );
</script>

<style scoped>
.text-neutral:hover {
    color: var(--primary);
}
</style>