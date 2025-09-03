# ============================
# 1. Imagen base (PHP 8.3 + Composer)
# ============================
FROM php:8.3-fpm

# Instalar dependencias del sistema y extensiones de PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ============================
# 2. Crear directorio de la app
# ============================
WORKDIR /var/www

# Copiar archivos de Laravel
COPY . .

# Asignar permisos correctos
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Instalar dependencias de PHP con Composer
RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist

# Generar key de la app si no existe
RUN php artisan key:generate || true

# Limpiar y generar cache de Laravel (evita fallos si algunos caches no existen)
RUN php artisan config:clear || true && \
    php artisan cache:clear || true && \
    php artisan config:cache || true && \
    php artisan route:cache || true && \
    php artisan view:cache || true

# ============================
# 3. Servidor
# ============================
EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
