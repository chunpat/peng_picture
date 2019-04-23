ARG PHP_VERSION
FROM php:${PHP_VERSION}

ARG PHP_SWOOLE_VERSION
ARG REPLACE_SOURCE_LIST
LABEL maintainer="zhangzhenpeng008@gmail.com"

# Timezone
RUN /bin/cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && echo 'Asia/Shanghai' > /etc/timezone
# Libs
COPY ./sources.list /etc/apt/sources.list.tmp
RUN if [ "${REPLACE_SOURCE_LIST}" = "true" ]; then \
    mv /etc/apt/sources.list.tmp /etc/apt/sources.list; else \
    rm -rf /etc/apt/sources.list.tmp; fi

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
# RUN docker-php-ext-install pdo_mysql

# zip extension
#RUN docker-php-ext-install zip

# Bcmath extension
#RUN docker-php-ext-install bcmath

# Redis extension
#RUN wget http://pecl.php.net/get/redis-${PHPREDIS_VERSION}.tgz -O /tmp/redis.tar.tgz \
#    && pecl install /tmp/redis.tar.tgz \
#    && rm -rf /tmp/redis.tar.tgz \
#    && docker-php-ext-enable redis

# Hiredis
#RUN wget https://github.com/redis/hiredis/archive/v${HIREDIS_VERSION}.tar.gz -O hiredis.tar.gz \
#    && mkdir -p hiredis \
#    && tar -xf hiredis.tar.gz -C hiredis --strip-components=1 \
#    && rm hiredis.tar.gz \
#    && ( \
#    cd hiredis \
#    && make -j$(nproc) \
#    && make install \
#    && ldconfig \
#    ) \
#    && rm -r hiredis

# Swoole extension
RUN wget https://github.com/swoole/swoole-src/archive/v${PHP_SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
    cd swoole \
    && phpize \
#    && ./configure --enable-async-redis --enable-mysqlnd --enable-openssl --enable-http2 \
    && ./configure --enable-openssl --enable-http2 \
    && make -j$(nproc) \
    && make install \
    ) \
    && rm -r swoole \
    && docker-php-ext-enable swoole

ADD . /var/www/peng_picture

WORKDIR /var/www/peng_picture

# Install
#RUN composer install --no-dev \
#    && composer dump-autoload -o \
#    && composer clearcache

EXPOSE 9510

ENTRYPOINT ["php", "/var/www/peng_picture/easyswoole", "start"]
