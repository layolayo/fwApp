<?php
    /*
        This is repersents a Specialism model
    */
    class Specialism {
        private $conn;

        public $email;
        public $password;

        public function __construct($db) {
            $this->conn = $db;

        }

        public function read() {
            $stmt = $this->conn->prepare("SELECT * FROM specialism "); 
            $stmt->execute();
            return $stmt->get_result();
        }

    }



?>
