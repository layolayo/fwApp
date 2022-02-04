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

        public function __construct($db = null) {
            $this->conn = $db;
            if($db == null) {
                $database = new Database();
                $this->conn = $database->connect();
            }
        }

        public function read() {
            /*This will be used for all the questions set within a phase class*/
            $stmt = $this->conn->prepare("SELECT * from phase_title ORDER BY ID ASC"); 
            $stmt->execute();
            return $stmt->get_result();
        }

    }



?>
