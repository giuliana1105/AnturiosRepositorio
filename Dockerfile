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
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
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

# Instalar dependencias de PHP con Composer
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

# Comando de inicio (PHP embebido en Laravel)
CMD php artisan serve --host=0.0.0.0 --port=8080
