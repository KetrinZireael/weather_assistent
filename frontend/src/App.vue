<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-b from-sky-50 to-white">
    <Navbar />
    <router-view class="flex-1" />
  </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue'
</script>

<style>
body {
  font-family: 'Inter', sans-serif;
}
</style>
