<template>
    <div class="container mx-auto px-4 py-5">
        <h2 class="h3 fw-bold text-neutral mb-4">Login</h2>
        <form @submit.prevent="login" class="col-md-6">
            <div class="mb-3">
                <label for="email" class="form-label fw-medium">Email</label>
                <input v-model="form.email" id="email" type="email" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label fw-medium">Password</label>
                <input v-model="form.password" id="password" type="password" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import { useAuthStore } from '../stores/auth';
import { toast } from 'vue3-toastify';

const form = ref( { email: 'mohamed@example.com', password: 'password' } );
const router = useRouter();
const authStore = useAuthStore();

onMounted( async () =>
{
    try
    {
        await api.get( 'http://localhost:8000/sanctum/csrf-cookie' );
    } catch ( error )
    {
        console.error( 'CSRF cookie error:', error );
    }
} );

const login = async () =>
{
    try
    {
        const response = await api.post( '/login', form.value );
        console.log( 'Login response:', response.data );
        if ( !response.data.data.token || !response.data.data.user )
        {
            throw new Error( 'Invalid login response: token or user missing' );
        }
        authStore.setToken( response.data.data.token );
        authStore.setUser( response.data.data.user.role );
        await router.push( '/dashboard' );
        setTimeout( () => toast.success( 'Logged in successfully' ), 500 );
    } catch ( error )
    {
        const errorMessage = error.response?.data?.message || 'Login failed';
        toast.error( errorMessage );
        console.error( 'Login error:', error.response?.data || error.message );
    }
};
</script>