<?php
/// Handler for retrieving a list of all question sets

require_once __DIR__ . '/../api_common.php';

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
if(!$stmt) {
    die($conn->error);
}
if(!$stmt->execute()) {
    die($conn->error);
}
$results = $stmt->get_result();
if(!$results) {
    die($conn->error);
}

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
