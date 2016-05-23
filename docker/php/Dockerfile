FROM php:5.6-fpm
MAINTAINER Paweł Kobylak <pawel@ncreations.pl>
WORKDIR /app

RUN echo 'date.timezone = "Europe/Warsaw"' > /usr/local/etc/php/conf.d/timezone.ini
RUN curl https://getcomposer.org/composer.phar -o /usr/sbin/composer
RUN chmod +x /usr/sbin/composer
RUN apt-get update && \
    apt-get install -y git && \
    apt-get autoremove && \
    apt-get clean
RUN docker-php-ext-install opcache pdo_mysql mbstring pcntl
ADD run.sh /run.sh
ADD entrypoint.sh /entrypoint.sh
ENTRYPOINT ["bash", "/entrypoint.sh"]
RUN grep export /entrypoint.sh >> ~/.bashrc
CMD ["/run.sh"]
