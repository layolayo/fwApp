<?php
require 'config.php';

    class Database
    {

        private $conn;

        public function connect()
        {

            $this->conn = new mysqli($_ENV['DBHOST'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DBNAME']);
            mysqli_select_db($this->conn, $_ENV['DBNAME']);
            if (!$this->conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            return $this->conn;
        }

    }
