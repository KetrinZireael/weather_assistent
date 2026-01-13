<template>
  <nav
    class="w-full bg-white/80 backdrop-blur-md shadow-sm border-b border-sky-100 fixed top-0 left-0 z-50"
  >
    <div
      class="max-w-5xl mx-auto flex items-center justify-between px-6 py-4"
    >
      <!-- Logo -->
      <RouterLink
        to="/"
        class="text-2xl font-extrabold text-sky-700 hover:text-sky-900 transition"
      >
        WeatherWear 🌦️
      </RouterLink>

      <!-- Navigation links -->
      <ul class="hidden sm:flex items-center gap-6">
        <li v-for="link in links" :key="link.path">
          <RouterLink
            :to="link.path"
            class="text-sky-700 font-medium hover:text-sky-900 transition relative pb-1"
            :class="{ 'text-sky-900 font-semibold': isActive(link.path) }"
          >
            {{ link.name }}
            <span
              v-if="isActive(link.path)"
              class="absolute left-0 bottom-0 w-full h-[2px] bg-sky-500 rounded-full transition-all"
            ></span>
          </RouterLink>
        </li>
      </ul>

      <!-- Mobile menu (optional) -->
      <button
        @click="menuOpen = !menuOpen"
        class="sm:hidden text-sky-700 hover:text-sky-900 transition"
      >
        <svg
          v-if="!menuOpen"
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg
          v-else
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Mobile dropdown -->
    <div
      v-if="menuOpen"
      class="sm:hidden bg-white/90 backdrop-blur-md border-t border-sky-100 shadow-md"
    >
      <ul class="flex flex-col items-center py-4 gap-3">
        <li v-for="link in links" :key="link.path">
          <RouterLink
            :to="link.path"
            class="text-sky-700 font-medium hover:text-sky-900 transition"
            @click="menuOpen = false"
            :class="{ 'text-sky-900 font-semibold': isActive(link.path) }"
          >
            {{ link.name }}
          </RouterLink>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const menuOpen = ref(false)

// список сторінок
const links = [
  { name: 'Головна', path: '/' },
  { name: 'Рекомендації', path: '/details' },
  // { name: 'Профіль', path: '/profile' },
  // { name: 'Вхід', path: '/login' },
]

// активний маршрут
function isActive(path) {
  return route.path === path
}
</script>
