import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    root: 'frontend', // ← важливо! вказуємо шлях до твого фронтенд-проєкту
    plugins: [
        vue(),
        tailwindcss(),
    ],
    server: {
        port: 5174,
    },
    build: {
        outDir: '../public/build', // збірка для Laravel (опціонально)
        emptyOutDir: true,
    },
})
