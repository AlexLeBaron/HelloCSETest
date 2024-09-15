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
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Créer le répertoire avant de le définir comme répertoire de travail
RUN mkdir -p /var/www/html

# Execute necessary commands after copying files
WORKDIR /var/www/html

# Copy app files in the web repository
COPY . /var/www/html

# Install composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Activate Apache rewrite module for cleaner url
RUN a2enmod rewrite 

COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copy .env.example to .env
COPY .env.example .env

# Install Composer dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Create folder public/storage/images if it doesn't exist
RUN mkdir -p /var/www/html/public/storage/images

# Link storage folder to public folder
RUN php artisan storage:link

# Check for folders permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# RUN php artisan migrate:fresh and db:seed after waiting for mysql to be initiated
CMD bash -c "sleep 10 && php artisan migrate && php artisan db:seed && php artisan key:generate && apache2-foreground"

# Expose port 80
EXPOSE 80 