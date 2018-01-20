FROM php:7.2-fpm
MAINTAINER Alexandre GERMAIN <agermain@ithoughts.io>

RUN apt-get update && apt-get install -y nginx zlib1g-dev libicu-dev libpq-dev imagemagick git mysql-client git zip unzip\
	&& docker-php-ext-install opcache \
	&& docker-php-ext-install intl \
	&& docker-php-ext-install mbstring \
	&& docker-php-ext-install pdo_mysql \
	&& php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer \
	&& chmod +sx /usr/local/bin/composer

COPY *.json /var/www/html/
WORKDIR /var/www/html/
RUN composer install
COPY . /var/www/html

EXPOSE 9000

RUN mv /var/www/html/symfony.conf /etc/nginx/conf.d/000-default.conf

CMD ["nginx", "-g", "daemon off;"]