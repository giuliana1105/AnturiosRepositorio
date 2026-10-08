# ============================
# 1. Imagen base (PHP 8.2 + Composer)
# ============================
FROM php:8.2-cli

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
WORKDIR /var/www/html

# Copiar archivos de dependencias primero (mejor cache de Docker)
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist --no-scripts

# Copiar archivos de Node
COPY package.json package-lock.json ./
RUN npm ci

# Copiar el resto de los archivos de Laravel
COPY . .

# Ejecutar scripts de composer post-install
RUN composer run-script post-autoload-dump || true

# Build de assets con Vite
RUN npm run build

# Asignar permisos correctos
RUN chmod -R 775 storage bootstrap/cache

# Limpiar cache y optimizar para producción
RUN php artisan config:clear || true && \
    php artisan cache:clear || true && \
    php artisan route:cache || true && \
    php artisan view:cache || true

# ============================
# 3. Servidor
# ============================
EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
