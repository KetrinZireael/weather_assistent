import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import tailwindcss from '@tailwindcss/vite'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
    tailwindcss(), // ✅ Tailwind працює з Vite
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)), // alias для імпортів
    },
  },
  server: {
    port: 5174, // будь-який вільний порт
    proxy: {
      // ✅ Проксі для запитів до Laravel API під час розробки
      '/api': {
        target: 'http://localhost:8000', // Laravel backend
        changeOrigin: true,
        secure: false,
      },
    },
  },
  build: {
    // ✅ Шлях до публічної папки Laravel (з фронтенду йдемо на рівень вище → public)
    outDir: '../public/build',
    emptyOutDir: true,
    manifest: true, // ❗ Рекомендовано для Laravel — генерує manifest.json
    rollupOptions: {
      input: '/src/main.js', // або main.ts, залежно від твого проєкту
    },
  },
})
