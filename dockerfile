FROM php:8.2.0-apache

WORKDIR /var/www/html

RUN apt-get update && \
    apt-get install -y libzip-dev unzip && \
    docker-php-ext-install pdo_mysql zip && \
    a2enmod rewrite

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["apache2-foreground"]
