FROM php:7.3-fpm

RUN apt-get update \
    && apt-get install -y \
    libxml2-dev \
    && apt-get clean -y \
    && docker-php-ext-install soap \
    && apt-get install -y git \
    && apt-get install -y vim \
    && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN set -eux; apt-get update; apt-get install -y libzip-dev zlib1g-dev; docker-php-ext-install zip

COPY ./php.ini /usr/local/etc/php/php.ini
