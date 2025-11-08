<template>
  <section class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-b from-sky-100 to-white p-6">
    <h1 class="text-4xl font-bold text-sky-900 mb-6 text-center">
      WeatherWear 🌤️
    </h1>

    <!-- Поле для введення міста -->
    <form @submit.prevent="getWeather" class="flex flex-col sm:flex-row gap-3 mb-6">
      <input
        v-model="city"
        type="text"
        placeholder="Введи місто (наприклад, Kyiv)"
        class="border border-sky-300 rounded-lg px-4 py-2 text-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-500"
      />
      <button
        type="submit"
        class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 py-2 rounded-lg shadow transition"
      >
        Показати погоду
      </button>
    </form>

    <!-- Стани -->
    <div v-if="loading" class="text-sky-700">Завантаження...</div>
    <div v-else-if="error" class="text-red-600">{{ error }}</div>

    <!-- Відображення погоди -->
    <div v-if="weather" class="text-center bg-white shadow-lg rounded-xl p-6 max-w-md w-full">
      <h2 class="text-2xl font-semibold text-sky-900 mb-2">
        {{ weather.city }}
      </h2>
      <p class="text-5xl font-light text-sky-800 mb-3">
        {{ Math.round(weather.temperature) }}°C
      </p>
      <p class="capitalize text-sky-700 mb-4">{{ weather.condition }}</p>
      <p class="text-sky-900 font-medium italic">
        {{ weather.advice }}
      </p>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const city = ref('Poltava')
const weather = ref(null)
const loading = ref(false)
const error = ref(null)

async function getWeather() {
  loading.value = true
  error.value = null
  weather.value = null

  try {
    const res = await fetch(`http://127.0.0.1:8000/api/weather?city=${encodeURIComponent(city.value)}`)
    const data = await res.json()

    if (res.ok) {
      weather.value = data
    } else {
      throw new Error(data.error || 'Помилка отримання даних')
    }
  } catch (err) {
    error.value = 'Не вдалося отримати погоду. Перевір назву міста.'
  } finally {
    loading.value = false
  }
}
</script>
