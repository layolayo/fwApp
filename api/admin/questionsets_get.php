<?php
/// Handler for retrieving a list of all phases

include_once '../../config/Database.php';
include_once '../../model/QuestionSet.php';

// If using the token override (mobile app when running in browser)
if (array_key_exists("HTTP_X_AUTH_TOKEN", $_SERVER ?? [])) {
    session_id($_SERVER["HTTP_X_AUTH_TOKEN"]);
}

session_start();

// Set cors header
header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, X-HTTP-Method-Override, X-Auth-Token");
header("Access-Control-Allow-Methods: GET,PUT,POST,DELETE,UPDATE,OPTIONS");
header('Access-Control-Allow-Credentials: true');


if($_ENV["AUTH_DISABLE"] !== true) {
    // Ensure that the requester is actually authenticated
    if (!array_key_exists("authenticated", $_SESSION ?? []) || $_SESSION["authenticated"] !== "authenticated") {
        die("Not authenticated");
    }

    // User must be an admin
    if (!array_key_exists("admin", $_SESSION) || $_SESSION["admin"] !== "admin") {
        die("Not authenticated");
    }
}

// Connect to db
$database = new Database();
$conn = $database->connect();
$stmt = $conn->prepare("SELECT question_set.*, (SELECT phase.title FROM phase WHERE phase.questionSetID=question_set.ID) as phase_title from question_set ORDER BY ID ASC");
$stmt->execute();
$results = $stmt->get_result();

$question = new QuestionSet();

$output = array();
while ($row = $results->fetch_assoc()) {
    $row_output = $row;

    // Get questions in question set
    $questions = array();
    foreach ($question->questions($row["ID"]) as $q) {
        $questions[] = $q;
    }
    $row_output["questions"] = $questions;

    // Get phases it's in

    $output[] = $row_output;
}

// Set content type
header('Content-Type: application/json');
echo json_encode($output);
