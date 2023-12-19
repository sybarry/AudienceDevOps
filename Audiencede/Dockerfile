FROM docker.io/php:7.4-fpm

RUN apt-get update && apt-get install -y \
               nginx \
               git \
               unzip

COPY nginx.conf /etc/nginx/nginx.conf

WORKDIR /var/www/html

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-interaction --no-plugins --no-scripts

CMD service php7.4-fpm start && nginx -g "daemon off;"