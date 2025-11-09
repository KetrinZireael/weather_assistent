# 1. Будуємо frontend (Vite)
FROM node:20 AS frontend
WORKDIR /app/frontend
COPY frontend/ .
RUN npm install && npm run build

# 2. Laravel backend
FROM php:8.2-apache
WORKDIR /var/www/html

# Копіюємо backend
COPY backend/ .

# Копіюємо зібраний frontend у публічну папку Laravel
COPY --from=frontend /app/frontend/dist /var/www/html/public

# Встановлюємо Composer і розширення PHP
RUN apt-get update && apt-get install -y git unzip && \
    docker-php-ext-install pdo pdo_mysql

# Встановлюємо Composer залежності
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --optimize-autoloader

# Права доступу
RUN chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Відкриваємо порт
EXPOSE 80

# Запускаємо сервер
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]