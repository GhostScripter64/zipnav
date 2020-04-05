FROM php:7.4.2-apache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
	&& apt-get update \
	&& apt-get install -y --no-install-recommends apt-utils git libicu-dev libzip-dev zip unzip \
	&& docker-php-ext-install intl pdo pdo_mysql \
	&& a2enmod rewrite headers

COPY . /var/www/html

WORKDIR /var/www/html/app
#RUN composer install
