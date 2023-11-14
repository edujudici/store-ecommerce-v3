# base image contains the dependencies
FROM php:8-fpm-alpine as base

RUN mkdir -p /var/www

WORKDIR /var/www

RUN apk add --update --no-cache \
    supervisor

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install pdo pdo_mysql pcntl

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis

RUN composer global require laravel/envoy --dev

COPY ./dockerfiles/php/supervisord.conf /etc/supervisor/conf.d/store-ecommerce.conf

# dev image inherits from base and not adds application code
FROM base as dev

RUN sed -i "s/user = www-data/user = root/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = root/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

USER root
CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]

# prod image inherits from base and adds application code
FROM base as prod

ARG UID
ARG GID
ARG USER

RUN addgroup -g ${GID} --system ${USER}
RUN adduser -G ${USER} --system -D -s /bin/sh -u ${UID} ${USER}

RUN sed -i "s/user = www-data/user = ${USER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = ${USER}/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

# COPY ./src /var/www
# RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

USER ${USER}
CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
