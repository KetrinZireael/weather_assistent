# Використовуємо офіційний PHP образ
FROM php:8.2-fpm

# Встановлюємо системні пакети
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копіюємо код проекту
WORKDIR /var/www/html
COPY . .

# Встановлюємо залежності Laravel
RUN composer install --no-dev --optimize-autoloader

# Генеруємо ключ Laravel
RUN php artisan key:generate

# Виставляємо права доступу
RUN chmod -R 777 storage bootstrap/cache

# Відкриваємо порт для Render
EXPOSE 10000

# Запускаємо сервер
CMD php artisan serve --host=0.0.0.0 --port=10000
