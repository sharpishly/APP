### READ ME ###

# Instructions
- See installation instructions below


# Project Features
- Sharpishly [live](https://sharpishly.com)
- Sharpishly [dev](https://dev.sharpishly.com)
- Sharpishly Docs : [Docs](/var/www/sharpishly_app/docs/html/index.html)
- Project Management : [Jira](https://sharpishly-project-management.atlassian.net/jira/software/projects/CRM/boards/1)
- Docker containers
- GitHub CI/CD [Workflow](https://github.com/sharpishly/APP/actions)
- Python Project : [Docs](/var/www/sharpishly_app/python_project/docs/html/index.html)
- Python MVC : [Python MVC](https://bitbucket.org/don_dev/php-mini-framework/src/master/)

# How to use website

- Navigation and site map



## Project structure📂:

### Sharpishly/

├── Assets            # Textures for game

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
-[Standalone](http://167.99.92.156:8000/)

## Installation

# Requirements
- PHP
- MySQL
- Nginx
- Linux
- pip3 install git-filter-repo

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

