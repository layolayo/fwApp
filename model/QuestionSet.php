<?php
    /*
        This is repersents the question set model
        This will select a question set depending on the phase
    */

    include_once 'Phase.php';
    include_once 'Category.php';

    class QuestionSet {
        private $conn;
    

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

        /**
         * question_sets | This is used for selecting every questions sets from
         * the database
         * @param none
         * @return mysqli_result | bool
         */
        public function question_sets() {
            $stmt = $this->conn->prepare("SELECT ID, title FROM question_set");
            $stmt->execute();
            return $stmt->get_result();
        }

         /**
         * question_set | This will be used for selecting a one question set
         * based on the id from the database
         * @param id: The id of the question set
         * @return mysqli_result:   The results sql result query
         * @return bool:    Returns false when an error occurs in the query. 
         */
        public function a_question_set($id) {
            $stmt = $this->conn->prepare("SELECT * FROM question_set WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            return $stmt->get_result();
        }

        /**
         * question_set_phase | This is used for collecting questions set in the database
         * depending on the phase
         * @param string $phase_title : The name of the phase.
         * @return string : Will return a string of the phase
         */
        public function question_set_phase($phase_title) {
            // Select a question based on phase
            // 1. gather question set from a particular phase
            $stmt = $this->conn->prepare("SELECT questionSetID FROM phase WHERE title = ?"); 
            $stmt->bind_param("s", $phase_title);
            $stmt->execute();

            // 2. select all the questions set which are not in a category 

            $result = $stmt->get_result();
            $question_set_IDS = array();
            if ($result) {
                // 2.A a while loop to graps all the questionSetIDs from the query.
                while ($row = $result->fetch_assoc()) {
                    $ID = $row['questionSetID'];
                    array_push($question_set_IDS, "'$ID'");
                }
            }
            return $question_set_IDS = implode("," , $question_set_IDS);
        }

        /**
         * uncategorised_question_set | This is used for collecting questions sets
         * within a phase which are not categorised.
         * @param string name:  The name of the phase
         * @return mysqli_result:   The results sql result query
         * @return bool:    Returns false when an error occurs in the query. 
         */
        public function uncategorised_question_set($phase_title) {
            // This selects a list of all uncategories question sets
            //$question_set_IDS = $this->question_set_phase($phase_title);

            $stmt = $this->conn->prepare("SELECT *, p.title as 'phase', q.title as 'questionSetName' FROM phase p,
            question_set q, type t
            WHERE p.title = ? AND p.questionSetID = q.ID
            AND q.type = t.title AND 
            NOT EXISTS (SELECT * FROM category c WHERE c.questionSetID = q.ID)
            ORDER BY q.frequency DESC");
            $stmt->bind_param("s", $phase_title);
            $stmt->execute();


            // select question set from ID. 
            return $stmt->get_result();

        }

        /**
         * categorised_question_set : This is used for collecting questions sets
         * within a phase which are categorised.
         * @param string name:  The name of the phase
         * @return mysqli_result:   The results sql result query
         * @return bool :   Returns false when an error occurs in the query.
         */
        public function categorised_question_set($phase_title) {
            
            $stmt = $this->conn->prepare("SELECT *, p.title as 'phase', q.ID as questionSetID , q.title as 'questionSetName'
            FROM phase p, category c,
            question_set q, type t
            WHERE p.title = ? AND p.questionSetID = q.ID
            AND q.type = t.title AND c.questionSetID = q.ID AND
            EXISTS (SELECT * FROM category c WHERE c.questionSetID = q.ID)
            ORDER BY q.frequency DESC");
            $stmt->bind_param("s", $phase_title);
            $stmt->execute();

            return $stmt->get_result();
        }
        /**
         * question | This will collect questions within a question set based
         * on the question set id
         * @param string id:    This will return the id of the question
         * @return mysqli_result:   The results sql result query
         * @return bool :   Returns false when an error occurs in the query.
         */
        public function questions($id) {
            $stmt = $this->conn->prepare("SELECT * FROM ask, question WHERE ask.question = question.ID and questionSet = ? ORDER BY qOrder ASC"); 
            $stmt->bind_param("s", $id);
            $stmt->execute();

            return $stmt->get_result();
        }

        /**
         * question_set_specilism_uncategorised | This will collect a list of uncategorised questions set 
         * based on the phase and the specilism
         * @param string phase_title:   The name of the phase
         * @param string specialism:    The name of the specialism
         * @return mysqli_result:   The results sql result query
         * @return bool :   Returns false when an error occurs in the query.
         */
        public function question_set_specilism_uncategorised($phase_title, $specialism) {
            // This will return an array of questions relating to a question set
            $stmt = $this->conn->prepare("SELECT * , q.title as 'questionSetName' FROM phase p, 
            question_set q, type t
            WHERE p.title = ? AND 
            q.specialism IN ($specialism) AND p.questionSetID = q.ID
            AND q.type = t.title AND 
            NOT EXISTS (SELECT * FROM category c WHERE c.questionSetID = q.ID)
            ORDER BY q.frequency DESC");
            $stmt->bind_param("s", $phase_title);
            $stmt->execute();

            return $stmt->get_result();

        }

        /**
         * question_set_specilism_categorised | This will collect a list of categorised questions set 
         * based on the phase and the specilism
         * @param string phase_title:   The name of the phase
         * @param string specialism:    The name of the specialism
         * @return mysqli_result:   The results sql result query
         * @return bool :   Returns false when an error occurs in the query.
         */
        public function question_set_specilism_categorised($phase_title, $specialism) {
            // This will return an array of questions relating to a question set
            $stmt = $this->conn->prepare("SELECT * , q.title as 'questionSetName' FROM phase p,
            question_set q, type t
            WHERE p.title = ? AND 
            q.specialism IN ($specialism) AND p.questionSetID = q.ID
            AND q.type = t.title AND 
            EXISTS (SELECT * FROM category c WHERE c.questionSetID = q.ID)
            ORDER BY q.frequency DESC");
            $stmt->bind_param("s", $phase_title);
            $stmt->execute();

            return $stmt->get_result();
        }

         /**
         * question_set_type_uncategorised | This will collect a list of uncategorised questions set 
         * based on the phase and the type
         * @param string phase_title:   The name of the phase
         * @param string type:    The name of the type
         * @return mysqli_result:   The results sql result query
         * @return bool :   Returns false when an error occurs in the query.
         */
        public function question_set_type_uncategorised($phase_title, $type) {
            // This will return an array of questions relating to a question set
            $stmt = $this->conn->prepare("SELECT * , q.title as 'questionSetName' FROM phase p,
            question_set q, type t
            WHERE p.title = ? AND 
            q.type IN ($type) AND p.questionSetID = q.ID
            AND q.type = t.title AND 
            NOT EXISTS (SELECT * FROM category c WHERE c.questionSetID = q.ID)
            ORDER BY q.frequency DESC");
            $stmt->bind_param("s", $phase_title);
            $stmt->execute();

            return $stmt->get_result();

        }

        /**
         * question_set_type_categorised | This will collect a list of categorised questions set 
         * based on the phase and the type
         * @param string phase_title:   The name of the phase
         * @param string type:    The name of the type
         * @return mysqli_result:   The results sql result query
         * @return bool :   Returns false when an error occurs in the query.
         */
        public function question_set_type_categorised($phase_title, $type) {
            // This will return an array of questions relating to a question set
            $stmt = $this->conn->prepare("SELECT * , q.title as 'questionSetName' FROM phase p,
            question_set q, type t
            WHERE p.title = ? AND 
            q.type IN ($type) AND p.questionSetID = q.ID
            AND q.type = t.title AND 
            EXISTS (SELECT * FROM category c WHERE c.questionSetID = q.ID)
            ORDER BY q.frequency DESC");
            $stmt->bind_param("s", $phase_title);
            $stmt->execute();

            return $stmt->get_result();
        }

        /**
         * Increment the frequency of the given question set
         * @param $id The id of the question set
         * @return bool True if the frequency was increased, false otherwise
         */
        public function increment_frequency($id): bool
        {
            $stmt = $this->conn->prepare("UPDATE question_set SET frequency = frequency + 1 WHERE id = ?");
            $stmt->bind_param("s", $id);
            return $stmt->execute();
        }
    }
