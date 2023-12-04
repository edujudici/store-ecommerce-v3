# layer from local mode
FROM alpine:latest as local

WORKDIR /var/www

# Installing Services
RUN apk update
RUN apk add bash
RUN apk add openrc
RUN mkdir /run/openrc && touch /run/openrc/softlevel
RUN rc-status

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# NGinx
RUN apk add nginx
RUN rc-update add nginx
RUN rm -rf /etc/nginx
COPY docker/config/nginx /etc/nginx

# Supervisor
RUN apk add supervisor
RUN mkdir -p /etc/supervisor/conf.d/
COPY docker/config/supervisor/supervisord.conf /etc/supervisor/conf.d/store-ecommerce.conf

# Redis
# RUN mkdir -p /usr/src/php/ext/redis \
#     && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
#     && echo 'redis' >> /usr/src/php-available-exts \
#     && docker-php-ext-install redis

# PHP
RUN apk add --no-cache php82 \
    php82-fpm \
    php82-pdo_mysql \
    php82-mysqli \
    php82-mbstring \
    php82-xml \
    php82-xmlreader \
    php82-xmlwriter \
    php82-curl \
    php82-iconv \
    php82-zip \
    php82-gd \
    php82-fpm \
    php82-phar \
    php82-tokenizer \
    php82-session \
    php82-fileinfo \
    php82-simplexml \
    php82-pcntl \
    php82-posix

RUN ln -s /usr/bin/php82 /usr/bin/php

# Envoy
RUN composer global require laravel/envoy --dev

COPY docker/start.sh /usr/local/bin/start

USER root

EXPOSE 80
CMD ["/usr/local/bin/start"]

# layer from production mode
# FROM local as production

# ARG UID
# ARG GID
# ARG USER

# RUN addgroup -g ${GID} --system ${USER}
# RUN adduser -G ${USER} --system -D -s /bin/sh -u ${UID} ${USER}

# RUN sed -i "s/user = nobody/user = ${USER}/g" /etc/php82/php-fpm.d/www.conf
# RUN sed -i "s/group = nobody/group = ${USER}/g" /etc/php82/php-fpm.d/www.conf
# RUN echo "php_admin_flag[log_errors] = on" >> /etc/php82/php-fpm.d/www.conf

# USER ${USER}