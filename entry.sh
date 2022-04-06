#!/bin/bash
echo "###########################"
echo "###########################"
php artisan migrate
echo "###########################"
echo "###########################"
/usr/sbin/apache2ctl -DFOREGROUND
