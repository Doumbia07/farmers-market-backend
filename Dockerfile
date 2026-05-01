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

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-interaction --no-progress --no-scripts

# Generate key and run post-autoload-dump
RUN composer run-script post-autoload-dump --no-interaction || true

# Create storage folders and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache && chmod -R 775 storage bootstrap/cache

# Expose port
EXPOSE 8000

# Start server
CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
