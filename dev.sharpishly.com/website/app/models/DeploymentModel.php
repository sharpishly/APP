<?php

namespace App\Models;

class DeploymentModel extends Model {
    private $remote_host;
    private $username;
    private $password;
  
    public function run($remote_host, $username, $password) {
      $this->remote_host = $remote_host;
      $this->username = $username;
      $this->password = $password;
    }
  
    public function deploy() {
      // Deploy website from BitBucket repository
      $response = array(
        "status" => "OK",
        "message" => "Deployment initiated"
      );
  
      try {
        // Clone the BitBucket repository
        shell_exec("git clone https://bitbucket.org/$this->username/$this->remote_host.git");
  
        // Switch to the cloned repository
        $output = shell_exec("cd /path/to/cloned/repo && git checkout master");
        if ($output === null) {
          throw new Exception("Failed to switch to master branch");
        }
  
        // Create a new Nginx host configuration file
        $output = shell_exec("sudo nano /etc/nginx/sites-available/$this->remote_host.conf");
        if ($output === null) {
          throw new Exception("Failed to create Nginx host configuration file");
        }
  
        // Update the `/etc/hosts` file
        $output = shell_exec("sudo bash -c 'echo \"$this->remote_host    $this->remote_host\" >> /etc/hosts'");
        if ($output === null) {
          throw new Exception("Failed to update /etc/hosts file");
        }
  
        // Configure Nginx to use the new host configuration
        $output = shell_exec("sudo bash -c 'ln -s /etc/nginx/sites-available/$this->remote_host.conf /etc/nginx/sites-enabled/'");
        if ($output === null) {
          throw new Exception("Failed to configure Nginx");
        }
  
        // Restart the Nginx service
        $output = shell_exec("sudo service nginx restart");
        if ($output === null) {
          throw new Exception("Failed to restart Nginx service");
        }
  
        // Return response object with deployment status
        $response["data"] = array(
          "remote_host" => $this->remote_host,
          "username" => $this->username,
          "password" => $this->password
        );
      } catch (Exception $e) {
        $response["status"] = "ERROR";
        $response["message"] = $e->getMessage();
        $response["data"] = array(
          "error" => $e->getMessage()
        );
      }
  
      return $response;
    }
  
    public function mysql($db_name, $db_user, $db_password) {
      // Create a new MySQL user and grant privileges
      $response = array(
        "status" => "OK",
        "message" => "MySQL setup initiated"
      );
  
      try {
        // Create a new MySQL user
        shell_exec("sudo mysql -u root -e \"CREATE USER '$db_user'@'%' IDENTIFIED BY '$db_password';\"");
  
        // Grant privileges to the new MySQL user
        $output = shell_exec("sudo mysql -u root -e \"GRANT ALL PRIVILEGES ON *.* TO '$db_user'@'%' WITH GRANT OPTION;\"");
        if ($output === null) {
          throw new Exception("Failed to grant privileges");
        }
  
        // Create a new MySQL database
        $output = shell_exec("sudo mysql -u root -e \"CREATE DATABASE `$db_name`;\"");
  
        // Update the MySQL configuration file to use the new user and password
        $output = shell_exec("sudo bash -c 'sed -i s/\/etc\/my.cnf.Original/\/etc\/my.cnf/g /etc/my.cnf'");
        if ($output === null) {
          throw new Exception("Failed to update MySQL configuration file");
        }
  
        // Restart the MySQL service
        $output = shell_exec("sudo service mysql restart");
        if ($output === null) {
          throw new Exception("Failed to restart MySQL service");
        }
  
        // Return response object with MySQL setup status
        $response["data"] = array(
          "db_name" => $db_name,
          "db_user" => $db_user,
          "db_password" => $db_password,
          "mysql_config_file" => "/etc/my.cnf"
        );
      } catch (Exception $e) {
        $response["status"] = "ERROR";
        $response["message"] = $e->getMessage();
        $response["data"] = array(
          "error" => $e->getMessage()
        );
      }
  
      return $response;
    }
  }

?>