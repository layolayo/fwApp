<?php
    /*
        This is repersents a the preset process model
    */
    class Preset {
        private $conn;
        private $table = "preset_process";

        public $id;
        public $email;
        public $password;
        public $disable;
        public $superuser;

        public function __construct($db, $name = Null) {
            $this->conn = $db;
            $names = array();

            $query = 'SELECT 
                      * 
                      FROM' . $this->table . 'ORDER BY frequency DESC'; 

            $stmt = $this->conn->query($query);

            if ($stmt) {
                if ($name != Null) {
                    $names = $stmt['title'];
                    $query = "SELECT title FROM". $this->table . "WHERE id IN " . $names . 'ORDER BY frequency DESC';
                    $stmt = $this->conn->query($query) or die(mysqli_error());
                }
            } else {
                die(mysqli_error());
            }
           

            return $stmt;

        }

    }



?>