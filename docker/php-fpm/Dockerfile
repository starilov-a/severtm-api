FROM php:8.2-fpm-alpine

ARG HTTP_PROXY
ARG HTTPS_PROXY
ARG NO_PROXY

ENV http_proxy=${HTTP_PROXY} \
    https_proxy=${HTTPS_PROXY} \
    no_proxy=${NO_PROXY}

# Install packages
# Устанавливаем необходимые системные зависимости
RUN apk add --no-cache \
    curl \
    git \
    build-base \
    zlib-dev \
    oniguruma-dev \
    autoconf \
    bash \
    gcc \
    g++ \
    make \
    libtool \
    linux-headers

RUN docker-php-ext-install pdo pdo_mysql

ARG INSTALL_XDEBUG

RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
     pecl install xdebug && docker-php-ext-enable xdebug && COPY ./docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini; \
    fi;

# Configure non-root user.

ARG PGID
ARG PUID

RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

# Source code
COPY ./ /var/www
WORKDIR /var/www

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction

CMD php-fpm

EXPOSE 9000
