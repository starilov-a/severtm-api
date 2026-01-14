FROM nginx:alpine


ARG HTTP_PROXY
ARG HTTPS_PROXY
ARG NO_PROXY
ENV http_proxy=${HTTP_PROXY} \
    https_proxy=${HTTPS_PROXY} \
    no_proxy=${NO_PROXY}

COPY nginx.conf /etc/nginx/
COPY default.conf /etc/nginx/conf.d/

RUN apk update && apk upgrade && apk add --no-cache bash

ARG PGID
ARG PUID

RUN set -x ; \
    addgroup -g ${PGID} -S www-data ; \
    adduser -u ${PUID} -D -S -G www-data www-data && exit 0 ; exit 1

ARG PHP_UPSTREAM_CONTAINER=php-fpm
ARG PHP_UPSTREAM_PORT=9000

# Set upstream conf and remove the default conf
RUN echo "upstream php-upstream { server ${PHP_UPSTREAM_CONTAINER}:${PHP_UPSTREAM_PORT}; }" > /etc/nginx/conf.d/upstream.conf

EXPOSE 80
