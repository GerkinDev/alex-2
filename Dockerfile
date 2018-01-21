FROM richarvey/nginx-php-fpm:1.4.0
MAINTAINER Alexandre GERMAIN <agermain@ithoughts.io>

RUN apk update && apk add zip unzip;


COPY *.json /var/www/html/
ENV APP_ENV prod
ENV SKIP_COMPOSER true
ENV SYMFONY_PROJECT_DIR /var/www/html
WORKDIR /var/www/html/
RUN composer install --no-dev --optimize-autoloader
COPY . /var/www/html
RUN php bin/console cache:clear --env=prod --no-debug --no-warmup\
	&& php bin/console cache:warmup --env=prod

RUN mv /var/www/html/symfony.conf /etc/nginx/sites-available/default.conf\
	&& mv /var/www/html/start.sh /start.sh\
	&& rm /var/www/html/.env