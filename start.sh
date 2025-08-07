#!/bin/bash
cd /var/www/app
docker-compose down
docker-compose up -d
docker ps -a