FROM php:8.1-fpm-alpine

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN apk update
RUN apk upgrade
RUN apk add bash

WORKDIR /var/www/html