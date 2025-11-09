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
    tailwindcss(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  server: {
    port: 5173, // звичайний порт Vite
  },
  build: {
    outDir: 'dist', // ⚡️ основна зміна — збірка у frontend/dist
    emptyOutDir: true,
  },
  base: './', // ✅ щоб коректно працювали всі відносні шляхи на Railway
})
