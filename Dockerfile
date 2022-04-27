FROM ubuntu:20.04
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get upgrade -y && apt-get install -y software-properties-common nano htop git curl apache2 unzip nmap && apt-get --with-new-pkgs upgrade -y && apt-get install php-cli php-curl php-dom libapache2-mod-php php-mysql libapache2-mod-php -y && apt-get autoclean && apt-get autoremove -y && a2enmod rewrite && a2enmod headers && php-mbstring
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"
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
RUN echo 'Header set Access-Control-Allow-Origin "*"'>> /etc/apache2/sites-enabled/000-default.conf
RUN echo "</VirtualHost>" >> /etc/apache2/sites-enabled/000-default.conf
RUN mv .env.example .env
RUN composer install
RUN composer update
RUN php artisan optimize
RUN chmod +x /var/www/html/entry.sh

ENTRYPOINT ["/var/www/html/entry.sh"]
