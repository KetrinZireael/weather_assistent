<template>
  <section
    class="min-h-screen flex items-center justify-center bg-gradient-to-b from-sky-50 to-white px-4"
  >
    <div
      class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md border border-sky-100 transition-all"
    >
      <h1
        class="text-3xl font-extrabold text-sky-800 mb-6 text-center tracking-tight"
      >
        Реєстрація 🧍‍♀️
      </h1>

      <form @submit.prevent="register" class="flex flex-col gap-4">
        <input
          v-model="name"
          type="text"
          placeholder="Ім’я"
          required
          class="border border-sky-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 outline-none transition"
        />
        <input
          v-model="email"
          type="email"
          placeholder="Email"
          required
          class="border border-sky-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 outline-none transition"
        />
        <input
          v-model="password"
          type="password"
          placeholder="Пароль"
          required
          class="border border-sky-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 outline-none transition"
        />
        <input
          v-model="password_confirmation"
          type="password"
          placeholder="Підтверди пароль"
          required
          class="border border-sky-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 outline-none transition"
        />

        <button
          type="submit"
          class="bg-sky-500 hover:bg-sky-600 text-white py-2 rounded-lg font-semibold transition disabled:opacity-50"
          :disabled="loading"
        >
          {{ loading ? 'Реєстрація...' : 'Зареєструватись' }}
        </button>
      </form>

      <p class="text-sky-700 text-center mt-4">
        Уже маєш акаунт?
        <RouterLink to="/login" class="text-sky-600 hover:underline">
          Увійти
        </RouterLink>
      </p>

      <p v-if="error" class="text-red-500 text-center mt-4">{{ error }}</p>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { API_BASE } from '@/config'

const router = useRouter()
const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const error = ref('')
const loading = ref(false)

async function register() {
  error.value = ''
  loading.value = true

  try {
    const res = await fetch(`${API_BASE}/register`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: password_confirmation.value,
      }),
    })

    // якщо сервер не відповідає або повернув помилку
    if (!res.ok) {
      const errData = await res.json().catch(() => ({}))
      throw new Error(errData.message || 'Помилка реєстрації 😔')
    }

    const data = await res.json()

    // якщо токен прийшов
    if (data.token) {
      localStorage.setItem('token', data.token)
      router.push('/profile')
    } else {
      throw new Error('Сервер не повернув токен')
    }
  } catch (err) {
    error.value = err.message || 'Не вдалося підключитися до сервера 😢'
  } finally {
    loading.value = false
  }
}
</script>
