FROM php:8.3-fpm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Update package list and install required packages
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zlib1g-dev \
    libzip-dev \
    libmemcached-dev \
    libssl-dev \
    librabbitmq-dev \
    pkg-config

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    mysqli \
    pdo_mysql \
    zip

# Install and enable PECL memcached extension
RUN pecl install memcached && docker-php-ext-enable memcached

# Install and enable PECL amqp extension
RUN pecl install amqp && docker-php-ext-enable amqp

# Install additional PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql

WORKDIR /var/www/html

CMD composer install;php-fpm