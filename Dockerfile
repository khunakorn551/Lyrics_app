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
    && echo "post_max_size=10M" >> /usr/local/etc/php/conf.d/upload-limit.ini \
    && echo "display_errors=On" >> /usr/local/etc/php/conf.d/error-reporting.ini \
    && echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/error-reporting.ini \
    && echo "log_errors=On" >> /usr/local/etc/php/conf.d/error-reporting.ini \
    && echo "error_log=/var/log/php_errors.log" >> /usr/local/etc/php/conf.d/error-reporting.ini

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create .env file from example if it doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Set default database connection to PostgreSQL
RUN sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=pgsql/' .env

# Set environment variables
RUN echo "APP_ENV=production" >> .env \
    && echo "APP_DEBUG=true" >> .env \
    && echo "LOG_LEVEL=debug" >> .env \
    && echo "SESSION_DRIVER=database" >> .env \
    && echo "LOG_CHANNEL=daily" >> .env

# Generate application key if not exists
RUN php artisan key:generate --no-interaction

# Create startup script
RUN echo '#!/bin/bash\n\
echo "Starting application..."\n\
echo "Testing database connection..."\n\
php artisan db:monitor\n\
echo "Clearing caches..."\n\
php artisan config:clear\n\
php artisan cache:clear\n\
php artisan view:clear\n\
php artisan route:clear\n\
echo "Creating session table..."\n\
php artisan session:table\n\
echo "Running migrations..."\n\
php artisan migrate --force\n\
echo "Caching configurations..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
echo "Setting permissions..."\n\
chmod -R 775 storage\n\
chmod -R 775 bootstrap/cache\n\
echo "Starting Apache..."\n\
apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Expose port 80
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=30s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Start Apache with startup script
CMD ["/usr/local/bin/start.sh"]
