<?php

// headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once '../config/Database.php';
include_once '../model/QuestionSet.php';

$database = new Database();
$conn = $database->connect();
// one main read file depending it send back multiple

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
echo $url[4];
echo print_r(array_values($url));

$phase = $_GET["phase"];
if (isset($phase)) {
    $question = new QuestionSet($conn);
    echo $phase;
    $results = $question->read_categoried_question($phase);
    if ($results) {
        $output[] = array();
        while ($row = $results->fetch_assoc()) {
            $output[] = $row;
        }
        echo json_encode($output);
    } else {
        die("error: " . mysqli_error($conn));
    }
}
?>
