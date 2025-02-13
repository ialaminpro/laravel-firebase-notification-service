# Use the official PHP 8.2 FPM image as the base image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nano \
    supervisor \
    libpq-dev \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Set working directory
WORKDIR /var/www

# Copy existing application directory to the working directory
COPY . /var/www

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Copy the entrypoint script
COPY docker/entrypoint.local.sh /usr/local/bin/entrypoint.local.sh

# Make the entrypoint script executable
RUN chmod +x /usr/local/bin/entrypoint.local.sh

# Set the entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.local.sh"]
