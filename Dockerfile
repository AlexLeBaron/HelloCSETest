# Use official PHP image with Apache
FROM php:8.1-apache

# Install necessary Laravel extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Activate Apache rewrite module for cleaner url
RUN a2enmod rewrite

# Copy app files in the web repository
COPY . /var/www/html

# Copier le script wait-for-it
COPY ./wait-for-it.sh /usr/local/bin/wait-for-it.sh

# Donner les permissions d'exécution au script
RUN chmod +x /usr/local/bin/wait-for-it.sh

# Execute necessary commands after copying files
WORKDIR /var/www/html

# Copy .env.example to .env
COPY .env.example .env

# Install Composer dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate application key
RUN php artisan key:generate

# Link storage folder to public folder
RUN php artisan storage:link

# Check for folders permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN php artisan migrate --force

RUN php artisan db:seed

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]