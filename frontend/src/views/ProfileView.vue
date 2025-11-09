<template>
  <section class="min-h-screen bg-gradient-to-b from-sky-50 to-white py-12 px-6">
    <div class="max-w-3xl mx-auto bg-white/70 backdrop-blur-md border border-sky-100 shadow-lg rounded-3xl p-8">

      <!-- 🔹 Профіль користувача -->
      <div class="flex flex-col sm:flex-row items-center gap-6 mb-10">
        <img
          src="/images/user-avatar.png"
          alt="User avatar"
          class="w-24 h-24 rounded-full border-4 border-sky-300 shadow-md"
        />
        <div class="text-center sm:text-left">
          <h1 class="text-3xl font-bold text-sky-900 mb-1">Привіт, Кетрін 👋</h1>
          <p class="text-sky-700">Твій простір для відгуків і рекомендацій по погоді ☁️</p>
        </div>
      </div>

      <button
        @click="logout"
        class="mt-4 bg-red-400 hover:bg-red-500 text-white font-medium py-2 px-4 rounded-lg transition"
      >
        🚪 Вийти
      </button>

      <!-- 🔹 Форма -->
      <div class="bg-sky-50 border border-sky-100 rounded-2xl p-6 mb-10 shadow-inner">
        <h2 class="text-2xl font-semibold text-sky-800 mb-4 text-center">Залиш свій відгук 💬</h2>

        <form @submit.prevent="submitFeedback" class="flex flex-col gap-4">
          <div class="flex flex-col sm:flex-row gap-4">
            <input
              v-model="city"
              type="text"
              placeholder="Місто"
              class="flex-1 border border-sky-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400"
              required
            />

            <input
              v-model.number="temperature"
              type="number"
              placeholder="Температура (°C)"
              class="w-40 border border-sky-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400"
              required
            />
          </div>

          <textarea
            v-model="comment"
            placeholder="Як спрацювала порада?"
            rows="3"
            class="border border-sky-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400"
            required
          ></textarea>

          <button
            type="submit"
            class="bg-sky-500 hover:bg-sky-600 text-white font-semibold py-2 px-6 rounded-lg shadow transition"
          >
            💾 Зберегти відгук
          </button>
        </form>

        <transition name="fade">
          <div v-if="successMessage" class="mt-4 text-green-600 text-center font-medium">
            {{ successMessage }}
          </div>
        </transition>
      </div>

      <!-- 🔹 Список відгуків -->
      <div>
        <h2 class="text-2xl font-semibold text-sky-800 mb-3">Мої відгуки 📋</h2>
        <div v-if="feedback.length === 0" class="text-sky-600 italic">Поки немає відгуків...</div>

        <ul class="space-y-3">
          <li
            v-for="item in feedback"
            :key="item.id"
            class="group border border-sky-100 bg-sky-50/50 rounded-xl p-4 hover:shadow-md transition-all"
          >
            <div class="flex justify-between items-center mb-1">
              <p class="text-sky-900 font-semibold">
                {{ item.city }} — {{ item.temperature }}°C
              </p>
              <p class="text-sky-400 text-sm">{{ new Date(item.created_at).toLocaleDateString() }}</p>
            </div>
            <p class="text-sky-700">{{ item.comment }}</p>
          </li>
        </ul>
      </div>

      <div class="text-center mt-10">
        <RouterLink to="/" class="text-sky-600 hover:underline">
          ⬅ Повернутись на головну
        </RouterLink>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useRouter } from 'vue-router'


const city = ref('')
const temperature = ref('')
const comment = ref('')
const successMessage = ref('')
const feedback = ref([])
const router = useRouter()

async function loadFeedback() {
  const res = await fetch('http://127.0.0.1:8000/api/feedback')
  feedback.value = await res.json()
}

async function submitFeedback() {
  const res = await fetch('http://127.0.0.1:8000/api/feedback', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      city: city.value,
      temperature: temperature.value,
      comment: comment.value,
    }),
  })

  if (res.ok) {
    successMessage.value = 'Відгук збережено! 💚'
    city.value = ''
    temperature.value = ''
    comment.value = ''
    loadFeedback()
    setTimeout(() => (successMessage.value = ''), 3000)
  }
}

async function logout() {
  const token = localStorage.getItem('token')
  if (!token) return router.push('/login')

  await fetch('http://127.0.0.1:8000/api/logout', {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })

  localStorage.removeItem('token')
  router.push('/login')
}

onMounted(loadFeedback)
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
