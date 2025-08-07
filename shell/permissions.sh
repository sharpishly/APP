#!/bin/bash
chmod -R 755 /var/www/app/sharpishly.com/website/public
chmod -R 755 /var/www/app/dev.sharpishly.com/website/public
chown -R www-data:www-data /var/www/app/sharpishly.com/website/public
chown -R www-data:www-data /var/www/app/dev.sharpishly.com/website/public
chmod -R 755 /var/www/app/python_project
chown -R www-data:www-data /var/www/app/python_project
chmod -R 755 /var/www/app/node_project
chown -R www-data:www-data /var/www/app/node_project
chmod -R 755 /var/www/app/letsencrypt
chown -R root:root /var/www/app/letsencrypt