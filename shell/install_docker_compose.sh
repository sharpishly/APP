# Install Docker if not already installed
sudo apt-get update
# sudo apt-get install -y docker.io

# # Start and enable Docker
# sudo systemctl start docker
# sudo systemctl enable docker

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verify installation
docker-compose --version