FROM ubuntu:latest
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=America/Indiana/Indianapolis
RUN ln -snf /usr/share/zoneinfo/America/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get install -y apache2 locales && locale-gen en_US.UTF-8

RUN a2enmod alias && \
    a2enmod headers && \
    a2enmod remoteip && \
    a2enmod rewrite

RUN apt-get install -y \
    php-common \
    php-cli \
    php-dom \
    php-json \
    php-readline \
    php-mbstring \
    php-mysql \
    php-intl \
    php-zip \
    unzip \
    php-curl \
    php-ldap \
    php-xsl \
    libapache2-mod-php

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /srv/sites/blossom
COPY --chown=www-data:staff . /srv/sites/blossom

RUN ./phpunit -c src/Test/Unit.xml

EXPOSE 80
ENTRYPOINT ["apachectl", "-D", "FOREGROUND"]
