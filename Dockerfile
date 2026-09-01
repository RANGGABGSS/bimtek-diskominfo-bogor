FROM php:8.2-cli

WORKDIR /var/www/html

# Install system dependencies, Node.js, and PHP SQLite extension
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    libzip-dev \
    curl \
    && docker-php-ext-install pdo_mysql pdo_sqlite zip \ \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install frontend dependencies and build Vite
RUN npm install
RUN npm run build

# Laravel cache
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear

# Prepare directories
RUN mkdir -p database \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD sh -c "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"
