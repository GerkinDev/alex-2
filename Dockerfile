FROM richarvey/nginx-php-fpm:1.4.0
MAINTAINER Alexandre GERMAIN <agermain@ithoughts.io>

RUN apk update && apk add zip unzip;

RUN	deluser nginx\
	&& addgroup nginx\
	&& adduser -D -S -h /var/cache/nginx -s /sbin/nologin -G nginx nginx

COPY *.json /var/www/html/

ENV APP_ENV prod
ENV SKIP_COMPOSER 1
ENV SKIP_CHOWN 1
ENV SYMFONY_PROJECT_DIR /var/www/html

WORKDIR /var/www/html/
RUN chown -Rf nginx.nginx /var/www/html
USER nginx
RUN composer install --no-dev --optimize-autoloader
COPY . /var/www/html
USER root
RUN mv /var/www/html/symfony.conf /etc/nginx/sites-available/default.conf\
	&& mv /var/www/html/start.sh /start.sh\
	&& chmod 755 /start.sh\
	&& rm /var/www/html/.env\
	&& chown -Rf nginx.nginx /var/www/html
USER nginx
RUN php bin/console cache:clear --env=prod --no-debug --no-warmup\
	&& php bin/console cache:warmup --env=prod

USER root
