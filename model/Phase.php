<?php
    /*
        This is repersents a facilitator model
    */
    class Phase {
        private $conn;
        private $table = "phase";

        public $id;
        public $email;
        public $password;
        public $disable;
        public $superuser;

        public function __construct($db) {
            $this->conn = $db;

            $query = 'SELECT 
                      *
                      FROM ' . $this->table;


            $stmt = $this->conn->query($query) or die(mysqli_error());

            return $stmt;

        }

    }



?>