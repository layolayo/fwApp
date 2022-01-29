FROM php:apache
RUN docker-php-ext-configure mysqli && docker-php-ext-install -j$(nproc) mysqli && docker-php-ext-enable mysqli

ENV APACHE_DOCUMENT_ROOT /var/www/html/fwApp

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN mkdir /var/www/html/fwApp
COPY ./html/* /var/www/html/fwApp/
COPY ./html/* /var/www/html/fwApp/fwApp/html/
COPY ./css /var/www/html/fwApp/css/
COPY ./css /var/www/html/fwApp/fwApp/css/
COPY ./js /var/www/html/fwApp/js/
COPY ./js /var/www/html/fwApp/fwApp/js/

COPY ./api /var/www/html/fwApp/fwApp/api/
COPY ./model /var/www/html/fwApp/fwApp/model/
COPY ./config /var/www/html/fwApp/fwApp/config/
COPY ./config/.env-dev /var/www/html/fwApp/fwApp/config/.env
COPY ./vendor /var/www/html/fwApp/fwApp/vendor/
