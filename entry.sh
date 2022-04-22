#!/bin/bash
echo "###########################"
echo "###########################"
su -c "php artisan migrate" www-data
echo "###########################"
echo "###########################"
/usr/sbin/apache2ctl -DFOREGROUND
