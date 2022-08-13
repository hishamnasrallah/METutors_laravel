FROM ubuntu:20.04
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get upgrade -y && apt-get install -y software-properties-common nano htop git curl apache2 unzip nmap && apt-get --with-new-pkgs upgrade -y && apt-get install php-cli php-curl php-dom libapache2-mod-php php-mysql libapache2-mod-php php-mbstring -y && apt-get autoclean && apt-get autoremove -y && a2enmod rewrite && a2enmod headers
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
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
RUN sed -i "s/upload_max_filesize = 2M/upload_max_filesize = 100M/g" /etc/php/7.4/apache2/php.ini
RUN sed -i "s/upload_max_filesize = 2M/upload_max_filesize = 100M/g" /etc/php/7.4/cli/php.ini
RUN sed -i "s/post_max_size = 8M/post_max_size = 100M/g" /etc/php/7.4/apache2/php.ini
RUN sed -i "s/post_max_size = 8M/post_max_size = 100M/g" /etc/php/7.4/cli/php.ini

RUN sed -i "s/max_input_time = 60/max_input_time = 24000/g" /etc/php/7.4/apache2/php.ini
RUN sed -i "s/max_input_time = 60/max_input_time = 24000/g" /etc/php/7.4/cli/php.ini

RUN sed -i "s/max_execution_time = 30/max_execution_time = 24000/g" /etc/php/7.4/apache2/php.ini
RUN sed -i "s/max_execution_time = 30/max_execution_time = 24000/g" /etc/php/7.4/cli/php.ini

RUN sed -i "s/memory_limit = 128M/memory_limit = 12000M/g" /etc/php/7.4/apache2/php.ini
RUN sed -i "s/memory_limit = 128M/memory_limit = 12000M/g" /etc/php/7.4/cli/php.ini
ENTRYPOINT ["/var/www/html/entry.sh"]
