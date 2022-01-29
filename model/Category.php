<?php
    /*
        This is repersents the question set model
        This will select a question set depending on the phase
    */

    include_once 'Phase.php';

    class Category {
        private $conn;
        private $table = "category";

        public $id;
        public $email;
        public $password;
        public $disable;
        public $superuser;

        public function __construct($db) {
            $this->conn = $db;

        }

        public function read($phase_title) {
            // This will return a list of categories based on a phase
            $phase = new Phase($this->conn);
            $list_qs = $phase->list_qs($phase_title);
            
            $query = "SELECT * FROM " .$this->table. " WHERE questionSetID in " . $list_qs;
            return $this->conn->query($query); 
        }

        public function questionID($phase_title) {
            /**
             * This will fetch a list of question ID which are categoried
             */

            $result = $this->read($phase_title);
            if ($result) {
                // used for selecting the questions set
                // corresponding to a phase.
                $question_set_IDs = "(";
                while($row = $result->fetch_assoc()) {
                    $set = $row["questionSetID"];
                    $question_set_IDs .= "'$set'" . ",";
                }
                $question_set_IDs[strlen($question_set_IDs)-1] = ")";
                
            }
            return $question_set_IDs;
        }

        public function category_qs($questionSetID) {
            /**
             * This will return the category of a question set
             */
            $questionSetID = "'$questionSetID'";
            $query = "SELECT * FROM " .$this->table. " WHERE questionSetID = " . $questionSetID;
            return $this->conn->query($query); 
        }
    }



?>