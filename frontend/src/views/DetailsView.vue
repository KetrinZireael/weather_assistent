<template>
  <section class="min-h-screen bg-gradient-to-b from-sky-50 to-sky-100 py-12 px-6">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-8">
      <h1 class="text-3xl font-bold text-sky-800 mb-6 text-center">Детальні рекомендації 👕🧥</h1>

      <div class="grid sm:grid-cols-2 gap-6">
        <div
          v-for="item in outfits"
          :key="item.id"
          class="border border-sky-200 rounded-xl p-4 flex flex-col items-center text-center hover:shadow-md transition"
        >
          <img :src="item.icon" alt="" class="w-20 h-20 object-contain mb-3" />
          <h2 class="text-xl font-semibold text-sky-800 mb-2">{{ item.title }}</h2>
          <p class="text-sky-700 text-sm leading-relaxed">{{ item.description }}</p>
        </div>
      </div>

      <div class="text-center mt-10">
        <RouterLink
          to="/"
          class="text-sky-600 hover:underline font-medium"
        >
          ⬅ Повернутись на головну
        </RouterLink>
      </div>
    </div>
  </section>
</template>

<script setup>
const outfits = [
  {
    id: 1,
    title: '☀️ Спекотно',
    description: 'Вдягай легкий одяг: шорти, футболку або сарафан. Обовʼязково капелюшок і сонцезахисні окуляри 😎',
    icon: '/images/outfit-hot.png'
  },
  {
    id: 2,
    title: '🌤️ Тепло',
    description: 'Футболка, сорочка, легка куртка або светр. Ідеально для прогулянки або кави надворі ☕',
    icon: '/images/outfit-warm.png'
  },
  {
    id: 3,
    title: '🌧️ Дощ',
    description: 'Не забудь парасольку ☂️ чи дощовик! Водостійке взуття — мастхев 💧',
    icon: '/images/outfit-rain.png'
  },
  {
    id: 4,
    title: '🌥️ Прохолодно',
    description: 'Светр, джинси, куртка або худі. Добре підійде теплий напій і затишна атмосфера ☕',
    icon: '/images/outfit-cool.png'
  },
  {
    id: 5,
    title: '❄️ Холодно',
    description: 'Шапка, шарф, рукавиці і пуховик. Не виходь без термокружки з какао 🧣',
    icon: '/images/outfit-cold.png'
  }
]
</script>
