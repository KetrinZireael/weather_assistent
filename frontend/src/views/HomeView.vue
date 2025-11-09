<template>
  <section
    class="flex flex-col items-center min-h-screen bg-gray-50 px-4 pb-10 pt-24"
  >
    <!-- Заголовок -->
    <h1 class="text-4xl font-extrabold text-gray-800 mb-10 tracking-tight">
      WeatherWear ☁️
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
        class="flex-1 border border-gray-300 rounded-xl px-4 py-3 text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
      />
      <button
        type="submit"
        class="bg-sky-500 hover:bg-sky-600 text-white font-semibold px-6 py-3 rounded-xl shadow transition"
      >
        Показати
      </button>
    </form>

    <!-- Стан -->
    <div v-if="loading" class="text-sky-700">Завантаження даних...</div>
    <div v-else-if="error" class="text-red-600">{{ error }}</div>

    <!-- Поточна погода -->
    <div
      v-if="weather"
      class="w-full max-w-3xl bg-white rounded-3xl shadow-md border border-gray-200 p-8 flex flex-col gap-8 transition"
    >
      <!-- Місто + температура -->
      <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">{{ weather.city }}</h2>
          <p class="text-gray-500 capitalize">
            {{ weather.icon }} {{ weather.condition }}
          </p>
        </div>
        <div class="flex items-center gap-3">
          <span class="text-4xl font-semibold text-gray-900">
            {{ Math.round(weather.temperature) }}°C
          </span>
        </div>
      </div>

      <!-- Рекомендація -->
      <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">
          Рекомендовано на сьогодні
        </h3>
        <p
          class="text-gray-900 text-lg leading-relaxed whitespace-pre-line font-medium"
        >
          {{ weather.advice.main }}
        </p>
        <p
          v-if="weather.advice.fabric"
          class="text-gray-600 text-sm italic mt-2"
        >
          🧵 Тканини: {{ weather.advice.fabric }}
        </p>
        <p
          class="text-gray-500 text-sm mt-2"
          v-if="Math.abs(weather.temperature - weather.feels_like) >= 2"
        >
          Відчувається як {{ Math.round(weather.feels_like) }}°C через
          {{ weather.wind_speed }} м/с вітру
        </p>
      </div>

      <!-- Детальні рекомендації -->
      <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-4">
          Детальні рекомендації 👕
        </h3>
        <div
          class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-left"
        >
          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="font-semibold text-gray-800 mb-1">👕 Верхній одяг</p>
            <p class="text-gray-600 text-sm">
              {{
                weather.temperature <= 5
                  ? 'Пуховик або тепла куртка.'
                  : weather.temperature <= 15
                    ? 'Легка куртка або бомбер.'
                    : 'Футболка або сорочка.'
              }}
            </p>
          </div>

          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="font-semibold text-gray-800 mb-1">👖 Низ</p>
            <p class="text-gray-600 text-sm">
              {{
                weather.temperature <= 0
                  ? 'Теплі штани або джинси з підкладкою.'
                  : weather.temperature <= 15
                    ? 'Джинси або штани з щільної тканини.'
                    : 'Легкі штани або шорти.'
              }}
            </p>
          </div>

          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="font-semibold text-gray-800 mb-1">👟 Взуття</p>
            <p class="text-gray-600 text-sm">
              {{
                weather.humidity > 80
                  ? 'Вибери водостійке взуття.'
                  : weather.temperature <= 5
                    ? 'Черевики або зимове взуття.'
                    : 'Кросівки або мокасини.'
              }}
            </p>
          </div>

          <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <p class="font-semibold text-gray-800 mb-1">🧣 Аксесуари</p>
            <p class="text-gray-600 text-sm">
              {{
                weather.temperature <= 5
                  ? 'Шапка, шарф, рукавиці — щоб не змерзнути.'
                  : weather.temperature <= 15
                    ? 'Капюшон або легкий шарф.'
                    : 'Сонцезахисні окуляри або кепка.'
              }}
            </p>
          </div>
        </div>
      </div>

      <!-- Аналіз погоди -->
      <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-4">
          Аналіз погодних умов 🌦️
        </h3>

        <div
          class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center text-gray-700"
        >
          <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <p class="font-semibold text-gray-900">{{ weather.humidity }}%</p>
            <p class="text-sm">Вологість</p>
          </div>

          <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <p class="font-semibold text-gray-900">
              {{ weather.wind_speed }} м/с
            </p>
            <p class="text-sm">Вітер</p>
          </div>

          <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <p class="font-semibold text-gray-900">
              {{ Math.round(weather.feels_like) }}°C
            </p>
            <p class="text-sm">Відчувається як</p>
          </div>

          <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <p class="font-semibold text-gray-900">{{ weather.icon }}</p>
            <p class="text-sm">Погода</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Прогноз -->
    <div
      v-if="weather && weather.forecast"
      class="mt-12 w-full max-w-4xl flex flex-col gap-6"
    >
      <h3 class="text-xl font-semibold text-gray-800 text-center">
        Прогноз на кілька днів 🗓️
      </h3>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="day in weather.forecast"
          :key="day.date"
          class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 flex flex-col items-center text-center hover:-translate-y-1 transition"
        >
          <p class="text-gray-800 font-medium mb-1">
            {{ formatDate(day.date) }}
          </p>
          <p class="text-3xl font-semibold text-gray-900 mb-1">
            {{ Math.round(day.temperature) }}°C
          </p>
          <p class="text-gray-600 mb-2 capitalize">
            {{ day.icon }} {{ day.condition }}
          </p>
          <p class="text-gray-700 text-sm leading-snug whitespace-pre-line mb-1">
            {{ day.advice.main }}
          </p>
          <p v-if="day.advice.fabric" class="text-gray-600 text-xs italic">
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
      `http://127.0.0.1:8003/api/weather?city=${encodeURIComponent(city.value)}`
    )

    if (!res.ok) throw new Error('Не вдалося отримати погоду')

    const data = await res.json()
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

onMounted(() => getWeather())
</script>
