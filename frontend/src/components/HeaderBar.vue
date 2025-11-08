<template>
  <header class="flex justify-between items-center px-6 py-4 bg-sky-200 shadow">
    <h1 class="text-xl font-semibold text-sky-900">WeatherWear 🌤️</h1>
    <nav class="space-x-4">
      <RouterLink to="/" class="hover:underline text-sky-800">Головна</RouterLink>
      <RouterLink to="/profile" class="hover:underline text-sky-800">Мій кабінет</RouterLink>
    </nav>
  </header>
</template>

<script setup>
import { RouterLink } from 'vue-router'
</script>
