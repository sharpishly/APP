### READ ME ###

# Clone with Submodules

The easiest way is to use the --recurse-submodules flag when cloning. This command handles everything in one go.

```
git clone --recurse-submodules git@github.com:sharpishly/app.git

```

# Git pull

```
git pull origin master

```
# Start Docker

```

docker-compose down
docker-compose up -d

```

# Install Docker if not already installed
```
sudo apt-get update
# sudo apt-get install -y docker.io

# Start and enable Docker
sudo systemctl start docker
sudo systemctl enable docker

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verify installation
docker-compose --version

```

# Docker Logs

```
docker logs sharpishly_prod
docker logs sharpishly_dev
docker logs php_fpm
docker logs certbot
docker logs python_app
docker logs python_nginx
docker logs node_app
docker logs node_nginx

```

# Connectivity

```

curl http://localhost
curl http://localhost:8080
curl http://localhost:1000
curl http://localhost:2000

```

# Python Project

```
http://167.99.92.156:8000/

```

# Project Features

```
* Sharpishly Docs : /var/www/sharpishly_app/docs/html/index.html
* Docker containers
* GitHub Continous Delivery
* GitHub Continous Integration
* CertBot
* Nginx
* MySQL
* Linux
* Bash
* Python Project : /var/www/sharpishly_app/python_project/docs/html/index.html
* Python MVC : https://bitbucket.org/don_dev/php-mini-framework/src/master/
* PHP

```

# How to use website

```

* Navigation and site map

```