FROM debian:9

#meta
RUN apt-get update

#upgrade
RUN apt-get -y upgrade

#php
RUN apt-get -y install php php-cli php-common php-curl php-intl php-json php-mysql php-opcache php-xml php-mbstring php-redis

#composer
RUN apt-get -y install composer

#xdebug
RUN apt-get -y install php-xdebug

#enable rewrite
RUN a2enmod rewrite

#setup virtualhost
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

#run
WORKDIR /var/www/html

EXPOSE 80
CMD ["apachectl", "-D", "FOREGROUND"]
