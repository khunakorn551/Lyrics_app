# Use official PHP with Apache and Composer
FROM php:8.2-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    curl \
    npm \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    zip \
    gd \
    mbstring \
    exif \
    pcntl \
    bcmath \
    && rm -rf /var/lib/apt/lists/*

# Verify PostgreSQL extension installation
RUN php -m | grep pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app files
COPY . /var/www/html

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Copy custom Apache configuration
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Configure PHP
RUN echo "memory_limit=256M" > /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "upload_max_filesize=10M" >> /usr/local/etc/php/conf.d/upload-limit.ini \
    && echo "post_max_size=10M" >> /usr/local/etc/php/conf.d/upload-limit.ini

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create .env file from example if it doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Set default database connection to PostgreSQL
RUN sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=pgsql/' .env

# Generate application key if not exists
RUN php artisan key:generate --no-interaction

# Clear and cache configuration
RUN php artisan config:clear \
    && php artisan cache:clear \
    && php artisan view:clear \
    && php artisan route:clear \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Create a health check script
RUN echo '#!/bin/bash\n\
curl -f http://localhost/ || exit 1' > /usr/local/bin/healthcheck.sh \
    && chmod +x /usr/local/bin/healthcheck.sh

# Expose port 80
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=3 \
    CMD /usr/local/bin/healthcheck.sh

# Start Apache
CMD ["apache2-foreground"]
