# ========== Build Stage ==========
FROM composer:2 as vendor

WORKDIR /app

# Copy only composer files for better layer caching
COPY composer.json composer.lock ./

# Install dependencies (no dev dependencies for production)
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

# ========== Final Stage ==========
FROM php:8.2-apache

# Install required system packages
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd \
        --withjpeg \
        --withfreetype \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        gd \
        zip \
        mbstring \
        xml \
        ctype \
        tokenizer \
        bcmath

# Enable Apache mod_rewrite
RUN a2enmod rewrite headers

# Copy application source
COPY --from=vendor /app/vendor /var/www/html/vendor
COPY . /var/www/html

# Set correct document root for Laravel
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Create required directories with correct permissions
RUN mkdir -p /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
