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

        public function read($title) {
            /*This will be used for all the questions set within a phase class*/
        
            $title = "'$title'";
            $query = 'SELECT * FROM '.$this->table. ' WHERE title = '.$title;
            return $this->conn->query($query); 
        }
        public function list_qs($title) {
            /**
             * This will return a list of questionsetID of question sets
             * based on the phase
             */
            $result = $this->read($title);
            if ($result) {
                // used for selecting the questions set
                // corresponding to a phase.
                $question_set = "(";
                while($row = $result->fetch_assoc()) {
                    $set = $row["questionSetID"];
                    $question_set .= "'$set'" . ",";
                }
                $question_set[strlen($question_set)-1] = ")";
                
            }
            return $question_set;
        }

        public function read_phase_titles() {
            $query = "SELECT * FROM phase_title ORDER BY ID ASC";
            return $this->conn->query($query); 
        }

    }



?>