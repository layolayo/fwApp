<?php
    /*
        This is repersents a Type model
    */
    class Type {
        private $conn;

        public $email;
        public $password;

        public function __construct($db) {
            $this->conn = $db;
            $this->email = "'$email'";
            $this->password = $password; // get email

        }

        public function read() {
            $stmt = $this->conn->prepare("SELECT * FROM type"); 
            $stmt->execute();
            return $stmt->get_result();
        }

    }



?>
