# 1️⃣ Білд фронтенду (Vite)
FROM node:20 AS frontend
WORKDIR /app/frontend
COPY frontend/package*.json ./
RUN npm install
COPY frontend/ .
RUN npm run build

# 2️⃣ Laravel backend
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Копіюємо Laravel-код
COPY backend/ .

# Копіюємо зібраний фронтенд у Laravel public
COPY --from=frontend /app/frontend/dist ./public

# Встановлюємо PHP залежності
RUN composer install --no-dev --optimize-autoloader

# Генеруємо ключ
RUN php artisan key:generate || true

RUN chmod -R 777 storage bootstrap/cache

EXPOSE 10000
CMD php artisan serve --host=0.0.0.0 --port=10000