<?php
session_start();

// Required to be the current hosting domain to allow sending cookies in xhttp requests
header('Access-Control-Allow-Origin: http://uniquechange.com');
// Required to be true to allow scripts to read response bodies from requests sent with cookies
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

include_once '../config/Database.php';
foreach (glob("../model/*.php") as $filename)
{
    include_once $filename;
}


class Request {
    private $context;
    private $database;
    private $conn;
   

    public function __construct($context) {
        $this->database = new Database();
        $this->conn = $this->database->connect();
        $this->context = $context;
    }

    public function JSON($results) {
        if ($results) {
            $output = array();
            while ($row = $results->fetch_assoc()) {
                $output[] = $row;
            }
            return json_encode($output);

        } else {
            die("error: " . mysqli_error($this->conn));
        }
    }

    /**
     * read_categoried_question:
     *  This will take in a list of question which are categoried.
     */
    public function read_categoried_question()
    {
        // http://www.uniquechange.com/fwApp/api/Request.php/phase/?phase=Main%20Course
        $phase = $_GET["phase"];
        if (isset($phase)) {
            $question = new QuestionSet($this->conn);
            $type = $this->typeArray($_GET["type"]);
            $specialism = $this->typeArray($_GET["specialism"]);
            if (empty($type) && empty($specialism)) {
                $results = $question->categorised_question_set($phase);
                $results = $this->filter_results_to_enabled_groups($results);
                return json_encode($results);
            } else {
                $output_specialism = array();
                $output_type = array();

                if (!empty($type)) {
                    $type = "'" . implode("', '", $type) . "'";
                    $results = $question->question_set_type_categorised($phase, $type);
                    while ($row = $results->fetch_assoc()) {
                        $output_type[] = $row;
                    }
                }
                if (!empty($specialism)) {
                    $specialism = "'" . implode("', '", $specialism) . "'";
                    $results = $question->question_set_specilism_categorised($phase, $specialism);
                    while ($row = $results->fetch_assoc()) {
                        $output_specialism[] = $row;
                    }
                }
                $results = array_merge($output_specialism, $output_type);
                $results = $this->filter_results_to_enabled_groups($results);
                return json_encode($results);
            }
        }
    }

    public function filter_results_to_enabled_groups($question_sets)
    {
        // Filter results to only contain question sets available in the current group

        // Are you in a group
        $database = new Database();
        $this->conn = $database->connect();
        $stmt = $this->conn->prepare("SELECT * FROM user_groups WHERE user_email = ?");
        $stmt->bind_param("s", $_GET["email"]);
        $stmt->execute();
        $user_groups_count = count($stmt->get_result()->fetch_all());
        if ($user_groups_count > 0) {
            // Get the question sets available to your group
            $stmt = $this->conn->prepare("SELECT * FROM `user_groups` INNER JOIN group_question_set ON group_question_set.group_id = user_groups.group_id WHERE user_email = ? ");
            $stmt->bind_param("s", $_GET["email"]);
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

    public function typeArray($type) {
        if (is_array($type) || empty($type)) {
            return $type;
        }
        return explode(",", $type);
    }

    public function read_uncategoried_question()
    {
        $phase = $_GET["phase"];
        if (isset($phase)) {
            $question = new QuestionSet($this->conn);
            $type = $this->typeArray($_GET["type"]);
            $specialism = $this->typeArray($_GET["specialism"]);
            if (empty($type) && empty($specialism)) {
                $results = $question->uncategorised_question_set($phase);
                $results = $this->filter_results_to_enabled_groups($results);
                return json_encode($results);
            } else {
                $output_specialism = array();
                $output_type = array();

                if (!empty($type)) {
                    $type = "'" . implode("', '", $type) . "'";

                    $results = $question->question_set_type_uncategorised($phase, $type);

                    while ($row = $results->fetch_assoc()) {
                        $output_type[] = $row;
                    }
                }
                if (!empty($specialism)) {
                    $specialism = "'" . implode("', '", $specialism) . "'";

                    $results = $question->question_set_specilism_uncategorised($phase, $specialism);

                    while ($row = $results->fetch_assoc()) {
                        $output_specialism[] = $row;
                    }
                }
                $results = array_merge($output_specialism, $output_type);
                $results = $this->filter_results_to_enabled_groups($results);
                return json_encode($results);
            }
        }
    }


    public function read_questions() {
        $id = $_GET["id"];
        if (isset($id)) {
            $question = new QuestionSet($this->conn);
            $results = $question->questions($id);
            return $this->JSON($results);
        }
    }

    public function read_titles_phases() {
        $phase = new Phase($this->conn);
        $results = $phase->read();
        return $this->JSON($results);
    }

    public function read_question_sets() {
        $question = new QuestionSet($this->conn);
        $results = $question->question_sets($id);
        return $this->JSON($results);
    }

    public function read_a_question_set() {
        $id = $_GET["id"];
        if (isset($id)) {
            $question = new QuestionSet($this->conn);
            $results = $question->a_question_set($id);
            return $this->JSON($results);
        }
    }

    public function read_titles_types() {
        $type = new Type($this->conn);
        $results = $type->read();
        return $this->JSON($results);
    }

    public function forgot_success() {
        $token = new Token($this->conn);
        $facilitator = new Facilitator($this->conn);
        $userToken = $_GET["token"];
        $userEmail = $_GET["email"];
        $resultsToken = $token->read($userToken);
        $resultsEmail = $facilitator->read($userEmail);

        $outputToken = $resultsToken->fetch_assoc();
        $outputEmail = $resultsEmail->fetch_assoc();
        
        if ($outputToken && $outputEmail ) {
            return "success";
        }
        return "failure";
    }

    public function new_password() {
        $facilitator = new Facilitator($this->conn);
        $userEmail = $_GET["email"];
        $userPassword = $_GET["password"];
        if ($facilitator->update($userEmail, password_hash($userPassword, PASSWORD_DEFAULT))) {
            $results = $facilitator->read($userEmail);
            $output = $results->fetch_assoc();
            if (password_verify($userPassword, $output["userpassword"]) == $userPassword) {
                return "success";
            }
        }
        return "failure";
    }

    public function read_titles_specialism() {
        $specialism = new Specialism($this->conn);
        $results = $specialism->read();
        return $this->JSON($results);
    }

    public function read_user_qs() {
        $facilitator = new Facilitator($this->conn);
        $userEmail = $_GET["email"];
        $results = $facilitator ->getUserQs($userEmail);
        return $this->JSON($results);
    }
}

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$requests = new Request($url[4]);
if ($url[4] == "categoried") {
    echo $requests->read_categoried_question();
} else if ($url[4] == "uncategoried") {
    echo $requests->read_uncategoried_question();
} else if ($url[4] == "type") {
    echo $requests->read_titles_types();
} else if ($url[4] == "specialism") {
    echo $requests->read_titles_specialism();
} else if ($url[4] == "forgot") {
    echo $requests->forgot_success();
}else if ($url[4] == "newpsw") {
    echo $requests->new_password();
}
?>
