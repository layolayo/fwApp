<?php
    /*
        This is repersents a facilitator model
    */
    class Facilitator {
        private $conn;
        private $table = "facilitator";

        public $id;
        public $email;
        public $password;
        public $disable;
        public $superuser;

        public function __construct($db) {
            $this->conn = $db;

            $query = 'SELECT 
                      id,
                      email,
                      userpassword,
                      disableuser,
                      superuser
                      WHERE 
                       email =' . $this->email .
                      'password = '. $this->password . 
                      'FROM' . $this->table;


            $stmt = $this->conn->query($query) or die(mysqli_error());

            return $stmt;

        }

    }



?>