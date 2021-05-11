<?php

    class Database {


        private $dbname = 'dbs1652193';
        private $username = 'dbu1420522';
        private $password = '7n6vunXeR2#iKRs';
        private $dbhost = 'db5002028125.hosting-data.io:3306';
        private $conn;

        public function connect() {

            $this->conn = new mysqli($this->dbhost, $this->username, $this->password, $this->dbname);

            if (!$this->conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            return $this->conn;
        }

    }

?>
