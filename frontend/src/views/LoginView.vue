<template>
  <section class="min-h-screen flex items-center justify-center bg-gradient-to-b from-sky-50 to-white">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md border border-sky-100">
      <h1 class="text-2xl font-bold text-sky-800 mb-6 text-center">Вхід до кабінету 🔑</h1>

      <form @submit.prevent="login" class="flex flex-col gap-4">
        <input v-model="email" type="email" placeholder="Email" required class="border border-sky-300 rounded-lg px-4 py-2" />
        <input v-model="password" type="password" placeholder="Пароль" required class="border border-sky-300 rounded-lg px-4 py-2" />

        <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white py-2 rounded-lg font-semibold transition">
          Увійти
        </button>
      </form>

      <p class="text-sky-700 text-center mt-4">
        Немає акаунта?
        <RouterLink to="/register" class="text-sky-600 hover:underline">Зареєструватись</RouterLink>
      </p>

      <p v-if="error" class="text-red-500 text-center mt-4">{{ error }}</p>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref('')

async function login() {
  error.value = ''
  try {
    const res = await fetch('http://127.0.0.1:8000/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: email.value, password: password.value }),
    })
    const data = await res.json()
    if (!res.ok) throw new Error(data.message || 'Помилка входу')

    localStorage.setItem('token', data.token)
    router.push('/profile')
  } catch (err) {
    error.value = err.message
  }
}
</script>
