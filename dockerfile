FROM php:8.1-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY php/ /var/www/html/
EXPOSE 80