<?php
/// Handler for retrieving a list of all user specific question sets

include_once '../config/Database.php';
include_once '../model/Facilitator.php';

session_start();

// Set cors header
header('Access-Control-Allow-Origin: *');

// Ensure that the requester is actually authenticated
if (!array_key_exists("authenticated", $_SESSION ?? []) || $_SESSION["authenticated"] !==  "authenticated") {
    die("Not authenticated");
}

$email = $_GET["email"] ?? die("Missing email");

// Connect to db
$question = new Facilitator();
$results = $question->getUserQs($email);

if(!$results) {
    die("Failed to get user questions");
}

// Set content type
header('Content-Type: application/json');

$output = array();
while ($row = $results->fetch_assoc()) {
    $output[] = $row;
}
echo json_encode($output);
