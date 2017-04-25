FROM php:5.6-fpm

RUN docker-php-ext-install pdo_mysql 

RUN docker-php-source extract \
    && curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/2.2.8.tar.gz \
    && tar xfz /tmp/redis.tar.gz \
    && rm -r /tmp/redis.tar.gz \
    && mv phpredis-2.2.8 /usr/src/php/ext/redis \
    && docker-php-ext-install redis
