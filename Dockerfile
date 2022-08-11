FROM ubuntu:20.04
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get upgrade -y && apt-get install -y software-properties-common nano htop git curl apache2 unzip nmap && apt-get --with-new-pkgs upgrade -y && apt-get install php-cli php-curl php-dom composer libapache2-mod-php php-mysql libapache2-mod-php -y && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && sudo apt-get install curl php-cli php-mbstring git unzip && apt-get autoclean && apt-get autoremove -y && a2enmod rewrite
WORKDIR /var/www/html
RUN rm index.html
COPY . .
RUN chown www-data:www-data . -R
RUN sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#g' /etc/apache2/sites-enabled/000-default.conf
RUN sed -i 's#</VirtualHost>##g' /etc/apache2/sites-enabled/000-default.conf
RUN echo "<Directory /var/www/html/public>" >> /etc/apache2/sites-enabled/000-default.conf
RUN echo "Options Indexes FollowSymLinks MultiViews" >> /etc/apache2/sites-enabled/000-default.conf
RUN echo "AllowOverride All" >> /etc/apache2/sites-enabled/000-default.conf
RUN echo "</Directory>" >> /etc/apache2/sites-enabled/000-default.conf
RUN echo "</VirtualHost>" >> /etc/apache2/sites-enabled/000-default.conf
RUN mv .env.example .env
RUN composer install
RUN sed -i "s/upload_max_filesize = 2M/upload_max_filesize = 100M/g" /etc/php/7.4/apache2/php.ini
ENTRYPOINT ["/usr/sbin/apache2ctl", "-DFOREGROUND"]
