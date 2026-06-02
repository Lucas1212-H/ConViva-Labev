# Multi-stage build for Laravel backend
# Builder: install PHP dependencies via Composer
FROM composer:2 AS builder
WORKDIR /app
COPY composer.json composer.lock ./

# COPIAR o código antes, mas usando a flag --no-scripts para o composer não quebrar sem as pastas do Laravel
COPY . /app
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Runtime: PHP with extensions
FROM php:8.1-cli
WORKDIR /app

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libzip-dev zip unzip libpng-dev libonig-dev libxml2-dev git curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif bcmath pcntl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy application from builder (includes vendor)
COPY --from=builder /app /app

# Garantir que as pastas existam E tenham a permissão correta ANTES de qualquer comando
RUN mkdir -p /app/storage /app/bootstrap/cache \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV PORT=8080

EXPOSE 8080

# Render-friendly startup: executa o package:discover que pulamos antes, limpa/gera caches, migra e inicia
CMD ["sh","-lc","php artisan package:discover --ansi && php artisan config:cache || true && php artisan route:cache || true && php artisan view:cache || true && php artisan migrate --force || true && php artisan serve --host=0.0.0.0 --port=${PORT}"]