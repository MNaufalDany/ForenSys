
# Stage 1 - Build frontend
FROM node:22-alpine AS frontend

WORKDIR /app
COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# Stage 2 - Laravel runtime
FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    nginx supervisor bash curl \
    icu-dev libzip-dev oniguruma-dev libxml2-dev \
    && docker-php-ext-install \
    pdo_mysql mbstring bcmath zip intl opcache dom

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts \
    && rm -f bootstrap/cache/packages.php \
             bootstrap/cache/services.php \
             bootstrap/cache/config.php

COPY --from=frontend /app/public/build ./public/build

COPY docker/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh

RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    /run/nginx \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data \
       storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/bin/sh", "/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
