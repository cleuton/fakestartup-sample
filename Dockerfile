FROM ubuntu:14.04

RUN echo "1.565.1" > .lts-version-number

RUN apt update && apt install -y wget git curl zip vim && \
    apt install -y postgresql-client && \
    apt install -y libpq-dev && \
    apt install -y apache2 php5 php5-pgsql && \
    apt install -y php5-intl imagemagick

RUN usermod -U www-data && chsh -s /bin/bash www-data

RUN echo 'ServerName ${SERVER_NAME}' >> /etc/apache2/conf-enabled/servername.conf

COPY enable-var-www-html-htaccess.conf /etc/apache2/conf-enabled/
COPY run_apache.sh /var/www/
ADD RandoSystem /var/www/html/
RUN ls /var/www/html
RUN a2enmod rewrite 