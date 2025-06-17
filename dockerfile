# Stage 1: Build assets
FROM node:18 AS frontend

WORKDIR /app

# Copy only the frontend files
COPY package*.json vite.config.js ./
COPY resources/ resources/
COPY public/ public/

RUN npm install
RUN npm run build

# Stage 2: Install PHP and Laravel dependencies
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev \
    && docker-php-ext-install pdo_pgsql zip

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy entire Laravel app
COPY . .

# Copy built assets from frontend stage
COPY --from=frontend /app/public/build public/build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Set Apache to serve from /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Install Laravel PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Clear cache
RUN composer clear-cache

# Copy file .env.example ke .env
RUN cp .env.example .env

# Generate app key
RUN php artisan key:generate

RUN php artisan storage:link

# Run database migrations
RUN php artisan migrate

# Expose port for Render
EXPOSE 8080

# Start Laravel server (or apache)
CMD ["apache2-foreground"]
