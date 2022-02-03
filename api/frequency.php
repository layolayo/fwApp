<?php
/// Handler for searching all question sets, based on a given query
/// A query can either be:
/// - a question set id
/// - some substring of the title (case insensitive)

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
