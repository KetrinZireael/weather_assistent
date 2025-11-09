# 1. Будуємо frontend (Vite)
FROM node:18 AS frontend
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

# Встановлюємо необхідні розширення PHP
RUN docker-php-ext-install pdo pdo_mysql

# Права на кеш і storage
RUN chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Відкриваємо порт
EXPOSE 80

# Запускаємо сервер
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
