FROM php:7.3-fpm-alpine

# Tools and PHP extensions
RUN apk update && apk add --update \
    grep \
    git \
    zip \
    curl \
    libcurl \
    libmemcached-dev \
    libmcrypt \
    libpq \
    libjpeg \
    postgresql-dev \
    libxml2-dev \
    autoconf \
    gcc \
    g++ \
    cyrus-sasl-dev \
    openssh \
    bash \
    unzip \
    postgresql-client \
    ca-certificates \
    wget \
    openssl \
    make \
    tzdata \
    zlib-dev \
    icu-dev \
    supervisor \
    libzip-dev

ENV TZ=Asia/Almaty

RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

# Install PHP Extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    bcmath \
    soap \
    intl \
    zip \
    xml \
    mbstring \
    exif \
    iconv

RUN apk add --no-cache --repository http://dl-3.alpinelinux.org/alpine/edge/testing gnu-libiconv
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

# Composer
RUN curl -sS "https://getcomposer.org/installer" | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer --version

RUN apk update && apk add --update nodejs npm && node -v

RUN touch /usr/local/etc/php/php.ini && \
    echo "memory_limit=256M" >> /usr/local/etc/php/php.ini && \
    echo "date.timezone=Asia/Almaty" >> /usr/local/etc/php/php.ini && \
    echo "post_max_size=50M" >> /usr/local/etc/php/php.ini && \
    echo "upload_max_filesize=50M" >> /usr/local/etc/php/php.ini

RUN addgroup -g 1000 -S user \
    && adduser -u 1000 -D -S -G user user

USER user

EXPOSE 9000
