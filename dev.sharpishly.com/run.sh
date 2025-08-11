#!/bin/bash

# stop nginx if running on host
sudo systemctl stop nginx
sudo systemctl disable nginx

docker-compose up -d
