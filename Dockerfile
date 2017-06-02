FROM php:7.0-cli
COPY . /code
WORKDIR /code
#RUN curl -O https://getcomposer.org/download/1.4.2/composer.phar
#RUN php composer.phar install
CMD ["php", "vendor/bin/phpunit" ]