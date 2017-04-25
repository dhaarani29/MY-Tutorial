FROM php:5.6-fpm

RUN docker-php-ext-install pdo_mysql

RUN apt-get update && apt-get install -y zlib1g-dev sendmail \
    && docker-php-ext-install zip

RUN docker-php-source extract \
    && curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/2.2.8.tar.gz \
    && tar xfz /tmp/redis.tar.gz \
    && rm -r /tmp/redis.tar.gz \
    && mv phpredis-2.2.8 /usr/src/php/ext/redis \
    && docker-php-ext-install redis

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

CMD echo $(grep $(hostname) /etc/hosts | head -n 1) $(grep $(hostname) /etc/hosts | head -n 1 | cut -f2).localdomain >> /etc/hosts \
    && service sendmail start && php-fpm 




