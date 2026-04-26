FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        default-mysql-client \
        curl \
        ca-certificates \
    && docker-php-ext-install mysqli pdo_mysql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . /var/www/html
COPY docker/php/php.ini /usr/local/etc/php/conf.d/pikachu.ini
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/pikachu-entrypoint

RUN chmod +x /usr/local/bin/pikachu-entrypoint \
    && chown -R www-data:www-data /var/www/html

EXPOSE 80

ENTRYPOINT ["pikachu-entrypoint"]
CMD ["apache2-foreground"]
