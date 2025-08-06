#!/bin/bash
sudo chmod -R 755 /var/www/app/sharpishly.com/website/public
sudo chmod -R 755 /var/www/app/dev.sharpishly.com/website/public
sudo chown -R www-data:www-data /var/www/app/sharpishly.com/website/public
sudo chown -R www-data:www-data /var/www/app/dev.sharpishly.com/website/public