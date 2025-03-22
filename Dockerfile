# Use official PHP FPM image
FROM php:8.3-fpm

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www

# Install dependencies
RUN composer install --optimize-autoloader

# Set correct permissions for cache/logs
RUN chown -R www-data:www-data /var/www/var/cache /var/www/var/log

# Expose port for Nginx
EXPOSE 9000

CMD ["php-fpm"]
