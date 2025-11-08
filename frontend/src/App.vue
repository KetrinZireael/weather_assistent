<template>
  <div class="min-h-screen bg-gradient-to-b from-sky-100 to-white">
    <HeaderBar />
    <router-view />
  </div>
</template>

<script setup>
import HeaderBar from './components/HeaderBar.vue'
</script>
