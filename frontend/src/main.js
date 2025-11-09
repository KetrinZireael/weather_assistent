import { createApp } from 'vue'
import App from './App.vue'
import router from './router' // ✅ додаємо імпорт router
import './assets/main.css'

// ✅ створюємо застосунок і підключаємо router
const app = createApp(App)
app.use(router)
app.mount('#app')
