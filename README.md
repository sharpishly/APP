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

# Directory list



### Notes

- **File Name Mismatch**: The script’s name, `directory_to_diagram.sh`, suggests it might create a visual diagram (e.g., a graphical output or ASCII art). If that’s the intent, integrating with `tree` or a diagramming tool like Graphviz could align better with the name. If it’s just a detailed listing, consider renaming the script to something like `recursive_dir_list.sh` for clarity.

- **Cross-Platform Compatibility**: The `ls -Rla` command is POSIX-compliant and works on most Unix-like systems. However, the output format of `ls` may vary slightly across systems (e.g., Linux vs. BSD). If portability is a concern, test the script on target systems.

- **Output Redirection**: Users might want to save the output to a file. You could add a note in the usage instructions, e.g., `./directory_to_diagram.sh /path > output.txt`.


### Testing the Script

To test the script:

1. Save it as `directory_to_diagram.sh`.

2. Make it executable: `chmod +x directory_to_diagram.sh`.

3. Run it with a valid directory: `./directory_to_diagram.sh /home/user/documents`.

4. Test edge cases:

   - No argument: `./directory_to_diagram.sh`

   - Invalid directory: `./directory_to_diagram.sh /nonexistent`

   - Directory without permissions: `./directory_to_diagram.sh /root`


If you have specific requirements (e.g., generating a graphical diagram, filtering certain files, or handling large directories), let me know, and I can tailor the script further!

The bash script you've provided is a robust tool for recursively listing the contents of a directory. It includes essential error-checking and offers a more readable output if the tree command is available. Let's break down the code and its functionality.
