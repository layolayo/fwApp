<?php
    /*
        This is repersents a facilitator model
    */
    class Facilitator {
        private $conn;
        private $table = "facilitator";

        public $email;
        public $password;

        public function __construct($db, $email, $password) {
            $this->conn = $db;
            $this->email = "'$email'";
            $this->password = $password; // get email

        }

        public function read() {
            $query = "SELECT id, email, userpassword, disableuser, superuser FROM ".$this->table." WHERE email = ".$this->email;
            $result = $this->conn->query($query);

            if (!$result) {
                return die("An Error: ". mysqli_error($conn));
            } 
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["userpassword"]) == $password) {
                return true;
            }
        }

    }



?>
