<?php
    /*
        This is would a model to repersent the question model
    */
    class Question {
        private $conn;
        private $table = "question";

        public $id;
        public $email;
        public $password;
        public $disable;
        public $superuser;

        public function __construct($db) {
            $this->conn = $db;

        }

        public function read($id) {
            $query = "SELECT * FROM" .$this->table. " WHERE ID = " .$id;
            return $this->conn->query($query);
        }

    }



?>