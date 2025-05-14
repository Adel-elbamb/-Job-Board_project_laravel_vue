<template>
  <div>
    <AppHeader />
    <router-view class="bg-light min-vh-100 py-4" />
  </div>
</template>

<script setup>
import AppHeader from './components/AppHeader.vue';
</script>