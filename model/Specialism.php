<?php
    /*
        This is repersents a Specialism model
    */
    class Specialism {
        private $conn;

        public $email;
        public $password;

        public function __construct($db = null) {
            $this->conn = $db;
            if($db == null) {
                $database = new Database();
                $this->conn = $database->connect();
            }
        }

        public function read() {
            $stmt = $this->conn->prepare("SELECT * FROM specialism "); 
            $stmt->execute();
            return $stmt->get_result();
        }

    }



?>
