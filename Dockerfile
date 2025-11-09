# 1️⃣ Фронтенд (Vite + Vue)
FROM node:20 AS frontend
WORKDIR /app/frontend

# Встановлюємо залежності
COPY frontend/package*.json ./
RUN npm install

# Копіюємо решту фронтенду та будуємо
COPY frontend/ .
RUN npm run build


# 2️⃣ Laravel backend
FROM php:8.2-fpm

# Встановлюємо системні залежності для PHP і SQLite
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Робоча директорія Laravel
WORKDIR /var/www/html

# Копіюємо Laravel код
COPY backend/ .

# Копіюємо зібраний фронтенд у Laravel public/build
COPY --from=frontend /app/public/build ./public/build

# Встановлюємо PHP-залежності
RUN composer install --no-dev --optimize-autoloader

# Генеруємо APP_KEY (ігноруємо помилку, якщо вже є)
RUN php artisan key:generate || true

# Даємо права для кешу та логів
RUN chmod -R 777 storage bootstrap/cache

# Відкриваємо порт (Railway очікує щось типу 10000)
EXPOSE 10000

# Запускаємо Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000