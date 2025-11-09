<template>
  <section
    class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-b from-sky-100 to-white px-6 py-12"
  >
    <h1 class="text-5xl font-extrabold text-sky-900 mb-10 text-center">
      WeatherWear 🌦️
    </h1>

    <!-- Поле введення міста -->
    <form
      @submit.prevent="getWeather"
      class="flex flex-col sm:flex-row gap-4 mb-10 w-full max-w-md"
    >
      <input
        v-model="city"
        type="text"
        placeholder="Введи місто (наприклад, Полтава)"
        class="flex-1 border border-sky-300 rounded-xl px-4 py-3 text-sky-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
      />
      <button
        type="submit"
        class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 py-3 rounded-xl shadow transition"
      >
        Показати
      </button>
    </form>

    <!-- Стани -->
    <div v-if="loading" class="text-sky-700">Завантаження даних...</div>
    <div v-else-if="error" class="text-red-600">{{ error }}</div>

    <!-- Поточна погода -->
    <div
      v-if="weather"
      class="w-full max-w-xl bg-white/90 backdrop-blur-md rounded-3xl shadow-lg border border-sky-100 p-8 flex flex-col items-center text-center transition-all"
    >
      <h2 class="text-3xl font-bold text-sky-800 mb-2">{{ weather.city }}</h2>
      <p class="text-sky-600 capitalize mb-6 text-lg">
        {{ weather.condition }}
      </p>

      <div class="flex flex-col sm:flex-row justify-center gap-8 mb-6">
        <div>
          <p class="text-6xl font-semibold text-sky-900">
            {{ Math.round(weather.temperature) }}°C
          </p>
          <p class="text-sky-600 text-sm">Температура</p>
        </div>

        <div>
          <p class="text-5xl font-medium text-sky-800">
            {{ Math.round(weather.feels_like) }}°C
          </p>
          <p class="text-sky-600 text-sm">Відчувається як</p>
          <p
            v-if="Math.abs(weather.temperature - weather.feels_like) >= 2"
            class="text-xs mt-1 text-sky-700 italic"
          >
            {{
              weather.feels_like < weather.temperature
                ? 'Здається холодніше 🧊'
                : 'Здається тепліше ☀️'
            }}
          </p>
        </div>
      </div>

      <!-- Додаткові параметри -->
      <div
        class="grid grid-cols-2 gap-4 text-sky-800 text-sm bg-sky-50 rounded-xl p-4 shadow-inner mb-6"
      >
        <div class="flex flex-col items-center">
          <span class="font-semibold text-sky-900 text-lg">{{ weather.humidity }}%</span>
          <span class="text-sky-700">Вологість</span>
        </div>
        <div class="flex flex-col items-center">
          <span class="font-semibold text-sky-900 text-lg">{{ weather.wind_speed }} м/с</span>
          <span class="text-sky-700">Вітер</span>
        </div>
      </div>

      <!-- Порада -->
      <div class="bg-sky-100/70 rounded-xl px-6 py-4 shadow-inner w-full">
        <p class="text-sky-900 font-medium text-lg leading-relaxed whitespace-pre-line mb-2">
          {{ weather.advice.main }}
        </p>
        <p
          v-if="weather.advice.fabric"
          class="text-sky-700 text-sm italic"
        >
          🧵 Рекомендовані тканини: {{ weather.advice.fabric }}
        </p>
      </div>
    </div>

    <!-- Прогноз на кілька днів -->
    <div v-if="weather && weather.forecast" class="mt-10 w-full max-w-3xl">
      <h3 class="text-2xl font-bold text-sky-900 mb-6 text-center">
        Прогноз на кілька днів 🗓️
      </h3>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="day in weather.forecast"
          :key="day.date"
          class="bg-white/80 border border-sky-100 rounded-2xl shadow-md p-5 flex flex-col items-center text-center hover:-translate-y-1 transition"
        >
          <p class="text-sky-800 font-semibold mb-2">
            {{ formatDate(day.date) }}
          </p>
          <p :class="['text-4xl font-bold mb-2', getTempColor(day.temperature)]">
            {{ Math.round(day.temperature) }}°C
          </p>
          <p class="text-sky-600 mb-3 capitalize">{{ day.condition }}</p>
          <p class="text-sky-700 text-sm leading-snug whitespace-pre-line mb-1">
            {{ day.advice.main }}
          </p>
          <p v-if="day.advice.fabric" class="text-sky-600 text-xs italic">
            🧵 {{ day.advice.fabric }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const city = ref('Poltava')
const weather = ref(null)
const loading = ref(false)
const error = ref(null)

async function getWeather() {
  loading.value = true
  error.value = null
  weather.value = null

  try {
    const res = await fetch(
      `http://127.0.0.1:8000/api/weather?city=${encodeURIComponent(city.value)}`
    )

    const contentType = res.headers.get('content-type')
    if (!contentType || !contentType.includes('application/json')) {
      throw new Error('Сервер повернув невалідні дані')
    }

    const data = await res.json()

    if (!res.ok) {
      throw new Error(data.error || 'Не вдалося отримати погоду')
    }

    // 👇 розділяємо поради та тканини
    const parseAdvice = (text) => {
      const parts = text.split('🧵 Тканини:')
      return {
        main: parts[0]?.trim() || '',
        fabric: parts[1]?.trim() || '',
      }
    }

    if (data.advice) data.advice = parseAdvice(data.advice)
    if (data.forecast) {
      data.forecast = data.forecast.map((day) => ({
        ...day,
        advice: parseAdvice(day.advice),
      }))
    }

    weather.value = data
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

function formatDate(dateStr) {
  const date = new Date(dateStr)
  return date.toLocaleDateString('uk-UA', {
    weekday: 'long',
    day: 'numeric',
    month: 'short',
  })
}

function getTempColor(temp) {
  if (temp >= 25) return 'text-red-600'
  if (temp >= 15) return 'text-yellow-600'
  if (temp >= 5) return 'text-sky-700'
  return 'text-blue-700'
}

onMounted(() => {
  getWeather()
})
</script>
