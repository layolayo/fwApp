<?php
    /*
        This is repersents a phase model
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
        }

        public function read() {
            /*This will be used for all the questions set within a phase class*/
            $stmt = $this->conn->prepare("SELECT * from phase_title ORDER BY ID ASC"); 
            $stmt->execute();
            return $stmt->get_result();
        }

    }



?>