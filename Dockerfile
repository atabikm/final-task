FROM php:8.3-fpm

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

RUN docker-php-ext-install pdo_mysql mbstring extsockets zip pcntl bcmath

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

EXPOSE 9000 8080
CMD ["php-fpm"]