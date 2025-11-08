<template>
  <div v-if="temperature !== null" class="mt-8 bg-white rounded-2xl shadow-lg p-6 max-w-md text-center">
    <h3 class="text-2xl font-bold text-sky-800 mb-3">Що вдягти сьогодні 👕</h3>

    <div class="flex flex-col gap-2 text-sky-700">
      <p v-if="temperature >= 25">☀️ Спекотно — обери шорти, футболку або легку сукню. Не забудь сонцезахисні окуляри 😎</p>
      <p v-else-if="temperature >= 15">🌤️ Тепло — футболка, сорочка чи легка куртка. Кросівки або кеди чудово підійдуть 👟</p>
      <p v-else-if="temperature >= 5">🌥️ Прохолодно — светр, джинси, куртка. Можеш додати шарф 🧣</p>
      <p v-else>❄️ Холодно — тепла куртка, шапка, шарф, рукавиці. Можна ще какао ☕</p>
    </div>

    <div class="flex justify-center mt-6">
      <img
        :src="getImageForTemp(temperature)"
        alt="outfit illustration"
        class="w-40 h-40 object-contain"
      />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  temperature: {
    type: Number,
    required: true
  }
})

const getImageForTemp = (temp) => {
  if (temp >= 25) return '/images/outfit-hot.png'
  if (temp >= 15) return '/images/outfit-warm.png'
  if (temp >= 5) return '/images/outfit-cool.png'
  return '/images/outfit-cold.png'
}
</script>
