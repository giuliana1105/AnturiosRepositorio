# ============================
# 1. Imagen base (PHP + Composer)
# ============================
FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones de PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    unzip \
    zip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ============================
# 2. Crear directorio de la app
# ============================
WORKDIR /var/www

# Copiar archivos de Laravel
COPY . .

# Instalar dependencias de PHP
RUN composer install --optimize-autoloader --no-dev

# Generar la cache de configuración de Laravel
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# ============================
# 3. Servidor
# ============================
# Render necesita que expongas un puerto, usaremos 8080
EXPOSE 8080

# Comando de inicio (usaremos PHP + servidor embebido)
CMD php artisan serve --host=0.0.0.0 --port=8080
