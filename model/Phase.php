<?php
    require_once '../config/Database.php';


    /*
        This is repersents a phase model
    */
    class Phase {
        private $conn;

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

        public function read_not_empty($email) {
            $output = array();

            $question = new QuestionSet();
            foreach ($this->read() as $row) {
                // Check if there are any question sets in this phase (filtered)
                $results = $question->categorised_question_set($row["title"]);
                $results2 = $question->uncategorised_question_set($row["title"]);
                $results = filter_results_to_enabled_groups2($results, $email);
                $results2 = filter_results_to_enabled_groups2($results2, $email);

                if (count($results) + count($results2) > 0) {
                    $output[] = $row;
                }
            }

            return $output;
        }

    }

function filter_results_to_enabled_groups2($question_sets, $email)
{
    // Filter results to only contain question sets available in the current group

    // Are you in a group
    $database = new Database();
    $conn = $database->connect();
    $stmt = $conn->prepare("SELECT * FROM user_groups WHERE user_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user_groups_count = count($stmt->get_result()->fetch_all());
    if ($user_groups_count > 0) {
        // Get the question sets available to your group
        $stmt = $conn->prepare("SELECT * FROM `user_groups` INNER JOIN group_question_set ON group_question_set.group_id = user_groups.group_id WHERE user_email = ? ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $groups_result = $stmt->get_result();

        $filtered_results = [];

        // Take only the questions that match the query and are in your available groups
        while ($row = $groups_result->fetch_assoc()) {
            foreach ($question_sets as $r) {
                if ($row["question_set_id"] == $r["ID"]) {
                    $filtered_results[] = $r;
                }
            }
        }
        return $filtered_results;
    } else {
        $unfiltered_results = [];
        foreach ($question_sets as $r) {
            $unfiltered_results[] = $r;
        }
        return $unfiltered_results;
    }
}



?>
