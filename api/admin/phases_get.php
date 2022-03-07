<?php
/// Handler for retrieving a list of all phases

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
$phase = new Phase($conn);
$results = $phase->read();
$question = new QuestionSet($conn);


if(!$results) {
    die("Failed to get phases");
}

$output = array();
while ($row = $results->fetch_assoc()) {
    $row_output = $row;

    $stmt = $conn->prepare("SELECT qs.* FROM question_set qs INNER JOIN phase ON phase.questionSetID = qs.ID WHERE phase.title = ?");
    $stmt->bind_param("s", $row['title']);
    $stmt->execute();
    $qs_results = $stmt->get_result();
    $question_sets = array();
    while($qs_row = $qs_results->fetch_assoc()) {
        $question_sets[] = $qs_row;
    }
    $row_output["question_sets"] = $question_sets;
    $output[] = $row_output;
}
// Set content type
header('Content-Type: application/json');
echo json_encode($output);
