FROM php:8.2-cli

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && \
    apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip \
    git

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY . /var/www/code/
WORKDIR /var/www/code/

RUN composer self-update --2 && composer install --no-dev
