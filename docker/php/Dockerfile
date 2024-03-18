FROM php:8.3.3-fpm

RUN export DEBIAN_FRONTEND=noninteractive \
    && apt-get update \
    && apt-get install -qq -y \
      curl \
      wget \
      git \
      zip unzip \
      libzip-dev \
      libmcrypt-dev \
      libpq-dev\
      zlib1g-dev \
    && pecl install mcrypt-1.0.7 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo_mysql \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-source delete \
    && apt-get remove -y g++ wget \
    && apt-get autoremove --purge -y && apt-get autoclean -y && apt-get clean -y \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* /var/tmp/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini
ADD php.ini /usr/local/etc/php/conf.d/php.ini

RUN addgroup --gid 3000 --system app
RUN adduser --uid 3000 --system --disabled-login --disabled-password --gid 3000 app

WORKDIR /var/www

CMD ["php-fpm"]