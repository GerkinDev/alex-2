FROM php:7-apache

RUN apt-get update -y; apt-get install -y git zip unzip; \
	curl -sS https://getcomposer.org/installer | php; mv composer.phar /usr/local/bin/composer

COPY *.json /var/www/html/
WORKDIR /var/www/html/
RUN composer install
COPY . /var/www/html