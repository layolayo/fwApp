<?php

header('Access-Control-Allow-Origin: *');
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

    /**
     * read_categoried_question:
     *  This will take in a list of question which are categoried.
     */
    public function read_categoried_question() {
        // http://www.uniquechange.com/fwApp/api/Request.php/phase?phase=Main%20Course
        $phase = $_GET["phase"];
        if (isset($phase)) {
            $question = new QuestionSet($this->conn);
            echo $phase;
            $results = $question->read_categoried_question($phase);
            if ($results) {
                $output[] = array();
                while ($row = $results->fetch_assoc()) {
                    $output[] = $row;
                }
                return json_encode($output);

            } else {
                die("error: " . mysqli_error($this->conn));
            }
        }

        return " ";
    }

    public function read_titles_phases() {
        $phase = new Phase($this->conn);
        $results = $phase->read_phase_titles();
        if ($results) {
            $output = array();
            while ($row = $results->fetch_assoc()) {
                $output[] = $row;
            }
            return json_encode($output);
        }
        return " ";
    }
}

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$requests = new Request($url[4]);
if ($url[4] == "phase") {
    echo $requests->read_categoried_question();
} else {
    echo $requests->read_titles_phases();
}
?>