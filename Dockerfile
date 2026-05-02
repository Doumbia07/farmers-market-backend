FROM dunglas/frankenphp:php8.4-bookworm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql \
    && apt-get clean

# Copy project files
COPY . /app
WORKDIR /app

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies (skip scripts to avoid missing .env errors)
RUN composer install --optimize-autoloader --no-interaction --no-progress --no-scripts

# Generate app key if not present
RUN composer run-script post-autoload-dump --no-interaction || true

# Create storage directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
