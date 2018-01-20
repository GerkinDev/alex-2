FROM phpmentors/symfony-app:latest

COPY *.json /var/app/web
WORKDIR /var/app/web
RUN composer install; \
	npm install;
COPY . /var/app/web