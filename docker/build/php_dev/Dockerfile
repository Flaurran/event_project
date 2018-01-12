FROM php:7.1-fpm

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
        libicu-dev \
        libicu52  \
        libxml2 \
        libxml2-dev \
        mysql-client \
    && docker-php-ext-install iconv \
    && docker-php-ext-install exif \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install pdo \
    && docker-php-ext-install intl \
    && docker-php-ext-install opcache \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install mcrypt 

# Customize default configuration
COPY zzz-config.conf /usr/local/etc/php-fpm.d/zzz-config.conf

# Create log directory
RUN mkdir /mnt/logs

# Enable developer goodness
COPY dev.conf /usr/local/etc/php-fpm.d/dev.conf

# Install developer tools
RUN apt-get update && apt-get -y install vim tree wget curl gettext-base
COPY .vimrc /root/.vimrc

WORKDIR /apps
