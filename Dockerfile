FROM php:7.3.6-fpm-alpine3.9
RUN apk add bash mysql-client --no-cache openssl nodejs npm
RUN docker-php-ext-install pdo pdo_mysql

ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

WORKDIR /var/www
RUN rm -rf /var/www/html

RUN ln -s public html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "memory_limit = -1" >> /usr/local/etc/php/conf.d/99-ajustes.ini


# RUN composer install && \
#   cp .env.example .env && \
#   php artisan key:generate && \
#   php artisan migrate

EXPOSE 9000

ENTRYPOINT ["php-fpm"]
