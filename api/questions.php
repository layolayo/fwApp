<?php
/// Handler for retrieving a list of all questions in a given question set

include_once '../config/Database.php';
include_once '../model/QuestionSet.php';

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
