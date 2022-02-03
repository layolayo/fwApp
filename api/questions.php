<?php
/// Handler for retrieving a list of all questions in a given question set

include_once '../config/Database.php';
include_once '../model/QuestionSet.php';

session_start();

// Set cors header
header('Access-Control-Allow-Origin: *');

// Ensure that the requester is actually authenticated
if (!array_key_exists("authenticated", $_SESSION ?? []) || $_SESSION["authenticated"] !==  "authenticated") {
    die("Not authenticated");
}

$id = $_GET["id"] ?? die("Missing id");

// Connect to db
$question = new QuestionSet();
$results = $question->questions($id);

if(!$results) {
    die("Failed to get questions");
}

// Set content type
header('Content-Type: application/json');

$output = array();
while ($row = $results->fetch_assoc()) {
    $output[] = $row;
}
echo json_encode($output);
