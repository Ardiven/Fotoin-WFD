# Gunakan image resmi PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Salin semua file ke dalam container
COPY . /var/www/html

# Set permission
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Aktifkan Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Jalankan Composer
RUN composer install

# Setelah Node.js dan npm sudah terinstall
RUN npm rebuild esbuild && npm install && npm run build




# Copy file .env.example ke .env
RUN cp .env.example .env

# Generate app key
RUN php artisan key:generate

# Jalankan database migrations
RUN php artisan migrate

RUN php artisan storage:link

# Expose port 80
EXPOSE 80