#!/bin/bash
echo "###########################"
echo "###########################"
php artisan migrate
echo "###########################"
echo "###########################"
chown -R www-data:www-data /var/www/html/storage/logs
/usr/sbin/apache2ctl -DFOREGROUND
