FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer via curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copy composer files first (for better caching)
COPY composer.json ./
RUN composer config --no-plugins allow-plugins.pestphp/pest-plugin true && \
    composer config --no-plugins allow-plugins.php-http/discovery true && \
    composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs || true

# Copy application code
COPY . .

# Create storage directories
RUN mkdir -p storage/framework/{cache,sessions,views} \
    bootstrap/cache \
    storage/logs \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x artisan

# Generate app key
RUN php artisan key:generate --force 2>/dev/null || true

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
