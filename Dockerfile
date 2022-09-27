FROM php:8.1-cli as dev

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY ./ ./

RUN composer install

ENTRYPOINT ["php", "index.php"]

#FROM php:8.1-cli as prod
#
#COPY --from=dev /app/test.php /app/test.php
#COPY --from=dev /app/src /app/src
#COPY --from=dev /app/vendor /app/vendor

