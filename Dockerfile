FROM php:7.4.28-apache
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN docker-php-ext-enable pdo pdo_mysql mysqli
RUN a2enmod rewrite
EXPOSE 80