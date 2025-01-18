FROM php:8.2-apache

RUN a2enmod rewrite ssl headers expires

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    poppler-utils \
    redis-server \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql zip

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN php -r "unlink('composer-setup.php');"

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install

EXPOSE 80

CMD ["apache2-foreground"]
