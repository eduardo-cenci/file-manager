FROM php:8.5.9-apache

WORKDIR /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip \
    && docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ARG APP_ENV

COPY php.ini-${APP_ENV} /usr/local/etc/php/php.ini

COPY composer.json composer.lock ./

RUN composer install --no-interaction --prefer-dist

COPY . .

RUN mkdir -p \
    /app/storage/files \
    /app/storage/logs/apache \
    /app/storage/logs/application

COPY apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
