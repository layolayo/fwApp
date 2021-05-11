<?php
    /*
        This is would a model to repersent the question model
    */
    class Question {
        private $conn;
        private $table = "question_set";

        public $id;
        public $email;
        public $password;
        public $disable;
        public $superuser;

        public function __construct($db, $id = NULL) {
            $this->conn = $db;
            $query = "";


            if ($id != NULL) {
                $query = 'SELECT 
                        * 
                        FROM' . $this->table . 'WHERE id = ' . $id ; 
            } else {
                $query = 'SELECT 
                      * 
                      FROM' . $this->table;
            }

            $stmt = $this->conn->query($query) or die(mysqli_error());

        
            
            return $stmt;

        }

    }



?>