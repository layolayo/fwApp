<?php
    /*
        This is repersents the question set model
    */
    class QuestionSet {
        private $conn;
        private $table = "question";

        public $id;
        public $email;
        public $password;
        public $disable;
        public $superuser;

        public function __construct($db, $category = Null) {
            $this->conn = $db;
            $query = "";
            $ids = array();


            $query = 'SELECT 
                      * 
                      FROM' . $this->table . 'ORDER BY frequency DESC'; 

            $stmt = $this->conn->query($query);

            if ($stmt) {
                if ($category != Null) {
                    $ids = $stmt['id'];
                    $query = "SELECT title FROM CATEGORY WHERE id IN " . $ids . 'ORDER BY frequency DESC';
                    $stmt = $this->conn->query($query) or die(mysqli_error());
                }
            } else {
                die(mysqli_error());
            }
            

            return $stmt;

        }

    }



?>