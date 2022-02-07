<?php
/// Handler for incrementing the frequency of a given question set id

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
$sets = $question->increment_frequency($id);

// Set content type
header('Content-Type: application/json');
echo "{}";
