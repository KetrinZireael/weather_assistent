#!/bin/bash
set -e

echo "🚀 Starting build process..."

# 1️⃣ Перехід у фронтенд і збірка Vue
if [ -d "frontend" ]; then
  echo "📦 Building frontend..."
  cd frontend
  npm ci
  npm run build
  cd ..
else
  echo "⚠️ Frontend folder not found — skipping build"
fi

# 2️⃣ Перехід у бекенд і підготовка Laravel
echo "🧩 Preparing Laravel..."
cd backend

# Встановлення залежностей Laravel
composer install --no-dev --optimize-autoloader

# Кешування конфігів і рутів
php artisan config:cache || true
php artisan route:cache || true

# 3️⃣ Запуск Laravel сервера на правильному порту
echo "🔥 Starting Laravel on port ${PORT:-8080}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}