FROM php:8.1-cli as dev

WORKDIR /app

RUN apt-get update && \
    apt-get install unzip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY index.php     /app/
COPY composer.json /app/
COPY composer.lock /app/

RUN composer install --no-dev --classmap-authoritative

ENTRYPOINT ["php", "/app/index.php"]

#FROM php:8.1-cli as prod
#
#COPY --from=dev /app/test.php /app/test.php
#COPY --from=dev /app/src /app/src
#COPY --from=dev /app/vendor /app/vendor

