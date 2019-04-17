FROM php:7.2

LABEL maintainer="zhangzhenpeng007@email.com"

# Version
ENV SWOOLE_VERSION 4.3.1

# Timezone
RUN /bin/cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo 'Asia/Shanghai' > /etc/timezone
# Libs
RUN apt-get update \
    && apt-get install -y \
    libmagickwand-dev \
    libmagickcore-dev \
    curl \
    wget \
    git \
    zip \
    libz-dev \
    libssl-dev \
    libnghttp2-dev \
    libpcre3-dev \
    && apt-get clean \
    && apt-get autoremove

# PDO extension
RUN docker-php-ext-install pdo_mysql

# zip extension
RUN docker-php-ext-install zip

# Bcmath extension
RUN docker-php-ext-install bcmath

# Redis extension
RUN wget http://pecl.php.net/get/redis-${PHPREDIS_VERSION}.tgz -O /tmp/redis.tar.tgz \
    && pecl install /tmp/redis.tar.tgz \
    && rm -rf /tmp/redis.tar.tgz \
    && docker-php-ext-enable redis

# Hiredis
RUN wget https://github.com/redis/hiredis/archive/v${HIREDIS_VERSION}.tar.gz -O hiredis.tar.gz \
    && mkdir -p hiredis \
    && tar -xf hiredis.tar.gz -C hiredis --strip-components=1 \
    && rm hiredis.tar.gz \
    && ( \
    cd hiredis \
    && make -j$(nproc) \
    && make install \
    && ldconfig \
    ) \
    && rm -r hiredis

# Swoole extension
RUN wget https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
    cd swoole \
    && phpize \
    && ./configure --enable-async-redis --enable-mysqlnd --enable-openssl --enable-http2 \
    && make -j$(nproc) \
    && make install \
    ) \
    && rm -r swoole \
    && docker-php-ext-enable swoole

ADD . /var/www/fashop

WORKDIR /var/www/fashop

# Install fashop
RUN composer install --no-dev \
    && composer dump-autoload -o \
    && composer clearcache

EXPOSE 9510

ENTRYPOINT ["php", "/var/www/fashop/fashop", "start"]
