# PHP-FPM Dockerfile — Laravel 12 环境
ARG PHP_VERSION=8.3
FROM php:${PHP_VERSION}-fpm-alpine

# 系统依赖
RUN apk add --no-cache \
    nginx \
    fcgi \
    postgresql-dev \
    mysql-client \
    mysql-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    libxpm-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    git \
    curl \
    zip \
    unzip \
    ffmpeg \
    $PHPIZE_DEPS

# PHP 扩展
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo_mysql \
        mysqli \
        zip \
        bcmath \
        opcache \
        pcntl \
        intl \
        mbstring \
        exif \
        sockets

# Redis 扩展
RUN pecl install redis-6.1.0 && docker-php-ext-enable redis

# Swoole / OpenSwoole (用于 Reverb + 高性能)
RUN pecl install swoole-5.1.2 && docker-php-ext-enable swoole

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 工作目录
WORKDIR /var/www/html

# 权限
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
