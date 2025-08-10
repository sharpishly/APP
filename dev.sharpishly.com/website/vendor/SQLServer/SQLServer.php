<?php

namespace SQLServer;

class SQLServer {

    private $connection;

    public function __construct($serverName, $databaseName, $username, $password) {
        try {
            $this->connection = new \PDO("sqlsrv:Server=$serverName;Database=$databaseName", $username, $password);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // Set error mode to exception
        } catch (\PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql); // Prepare the SQL statement
            $stmt->execute($params); // Execute with parameters for security (prevents SQL injection)
            return $stmt; // Return the statement object
        } catch (\PDOException $e) {
            throw new \Exception("Query execution failed: " . $e->getMessage());
        }
    }


    public function fetchAll($sql, $params = []) { // Convenience method to fetch all rows
        try {
            $stmt = $this->query($sql, $params);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC); // Fetch as associative array
        } catch (\PDOException $e) {
            throw new \Exception("Fetching data failed: " . $e->getMessage());
        }
    }

    public function fetchRow($sql, $params = []) { // Convenience method to fetch a single row
      try {
          $stmt = $this->query($sql, $params);
          return $stmt->fetch(\PDO::FETCH_ASSOC); // Fetch as associative array
      } catch (\PDOException $e) {
          throw new \Exception("Fetching data failed: " . $e->getMessage());
      }
  }


    public function close() {
        $this->connection = null; // Close the connection
    }

}
?>