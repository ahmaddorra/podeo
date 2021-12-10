#!/bin/sh

#pwd
#cd /podeo
php artisan storage:link
php artisan migrate:fresh
service apache2 start
#php artisan serve
#/etc/init.d/apache2 restart

#return
