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

        public function __construct($db) {
            $this->conn = $db;

        }

        public function read($name = NULL) {
            $names = array();

            $query = 'SELECT 
                      * 
                      FROM' . $this->table . 'ORDER BY frequency DESC'; 

            $result = $this->conn->query($query);

            if ($result) {
                if ($name != Null) {
                    $row = $result->fetch_assoc();
                    $title= $row['title'];
                    $query = "SELECT title FROM " .$this->table. " WHERE id IN" .$title.'ORDER BY frequency DESC';
                    $stmt = $this->conn->query($query) or die(mysqli_error());
                }
            } else {
                return die(mysqli_error());
            }
           
            return $stmt;
        }

    }

    //select * from information_schema.key_column_usage where constraint_schema = 'dbs1652193'

?>