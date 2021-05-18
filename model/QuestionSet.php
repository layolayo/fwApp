<?php
    /*
        This is repersents the question set model
        This will select a question set depending on the phase
    */

    include_once 'Phase.php';
    include_once 'Category.php';

    class QuestionSet {
        private $conn;
        private $table = "question_set";

        public $id;
        public $email;
        public $password;
        public $disable;
        public $superuser;

        public function __construct($db) {
            $this->conn = $db;

        }

        public function read($phase_title) {
            // This would returns a list of questions
            // based on a phase
            $phase = new Phase($this->conn);
            $list_qs = $phase->list_qs($phase_title);
            
            $query = "SELECT * FROM " .$this->table. " WHERE ID in " . $list_qs;
            return $this->conn->query($query);
        }

        public function read_categoried_question($phase_title) {
            // This will return a list of question set which are categoried
            $category = new Category($this->conn);
            $questionID = $category->questionID($phase_title);
            $query = "SELECT * FROM " .$this->table. " WHERE ID in " . $questionID;

            return $this->conn->query($query);
        }

        public function read_uncategoried_question($phase_title) {
            // This will return a list of question set which not categoried
            $category = new Category($this->conn);
            $questionID = $category->questionID($phase_title);
            $query = "SELECT * FROM " .$this->table. " WHERE ID not in " . $questionID;

            return $this->conn->query($query);
        }

        public function questions($question_set_title) {
            // This will return an array of questions relating to a question set
            $question_set_title = "'$question_set_title'";

            $query = "SELECT * FROM ask WHERE questionSet = " .$question_set_title; // select question in ask
            $result = $this->conn->query($query);
            if ($result) {
                $questions = array();
                while ($row = $result->fetch_assoc()) {
                    array_push($questions, $row["question"]);
                }
            }
            return $questions; // list of question search
        }

        public function list_qs_titles($title) {
            /**
             * This will return an array of titles
             * within a question set
             */

            $phase = new Phase($this->conn);
            $result = $phase->read($title);
            if ($result) {
                // used for selecting the questions set
                // corresponding to a phase.
                $titles = array();
                while($row = $result->fetch_assoc()) {
                    array_push($titles,$row["title"]);
    
                }
            }
            return $titles;
        }



    }



?>