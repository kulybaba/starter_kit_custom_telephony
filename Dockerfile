FROM php:8.4-fpm

RUN apt update \
    && apt install -y git zip unzip libzip-dev libmemcached-dev zlib1g-dev \
    && pecl install memcache \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable memcache

RUN apt clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["php-fpm"]
