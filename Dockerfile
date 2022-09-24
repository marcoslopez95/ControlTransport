FROM alpine:3.16
LABEL Maintainer="Zippyttech" \
      Description="Lightweight container with Nginx 1.14 & PHP-FPM 8 based on Alpine Linux. "
RUN    apk add --update curl ca-certificates

# Install packages
RUN apk --no-cache add php php-fpm php-pgsql php-pdo_pgsql  php-json php-openssl php-curl php-fileinfo \
    php-zlib php-xml php-phar php-intl php-dom php-xmlreader php-ctype php-iconv php-simplexml php-zip\
    php-mbstring php-gd php-xml php-soap php-xmlwriter php-tokenizer php-session nginx supervisor curl

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Configure nginx
COPY nginx/nginx.conf /etc/nginx/nginx.conf


# Configure PHP-FPM
COPY nginx/fpm-pool.conf /etc/php/php-fpm.d/zzz_custom.conf
COPY nginx/php.ini /etc/php/conf.d/zzz_custom.ini

# Configure supervisord
COPY nginx/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Environment Variables
ENV APP_LOG errorlog
# Add application
RUN mkdir -p /var/www/html
WORKDIR /var/www/html
RUN mkdir storage && mkdir public && mkdir nginx && mkdir app
COPY ["composer.json","./"] && ["storage","./storage"] && ["public","./public"] && ["nginx", "./nginx"]
#RUN ["chmod","755","./nginx/AccessLog.sh"]
RUN ["chmod","-R","777","storage"] && ["chmod","-R","777","public"]
RUN composer install
COPY [".", "./"]
RUN php artisan package:discover --ansi \
    && php artisan vendor:publish --tag=laravel-assets --ansi --force
EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
