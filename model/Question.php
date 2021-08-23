<?php
    /*
        This is would a model to repersent the question model
    */
    class Question {
        private $conn;

        public $id;
        public $email;
        public $password;
        public $disable;
        public $superuser;

        public function __construct($db) {
            $this->conn = $db;

        }

        public function read($id) {
            $stmt = $this->conn->prepare("SELECT * FROM question WHERE ID = ?"); 
            $stmt->bind_param("s", $id);
            $stmt->execute();

            return $stmt->get_result();
        }

    }



?>