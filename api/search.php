<?php
/// Handler for searching all question sets, based on a given query
/// A query can either be:
/// - a question set id
/// - some substring of the title (case insensitive)

include_once '../config/Database.php';
include_once '../model/QuestionSet.php';

$SEARCH_LIMIT_MAX = 10;

session_start();

// Set cors header
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

// Ensure that the requester is actually authenticated
if (!array_key_exists("authenticated", $_SESSION ?? []) || $_SESSION["authenticated"] !==  "authenticated") {
    die("Not authenticated");
}

$query = $_GET["q"] ?? die("No query given");
$limit = $_GET["l"] ?? 5;

// Restrict to max limit results, or given limit if less
$limit = min($limit, $SEARCH_LIMIT_MAX);

// Connect to db
$database = new Database();
$conn = $database->connect();
$question = new QuestionSet($conn);
$sets = $question->question_sets();

$results = [];

foreach ($sets as $set) {
    // If the query is part of the id or title add it to results
    if(str_contains($set["ID"], $query) || str_contains(strtolower($set["title"]), strtolower($query))) {
        $results[] = $set;
    }

    // If limit hit, exit
    if(count($results) >= $limit) {
        break;
    }
}

// Set content type
header('Content-Type: application/json');
echo json_encode($results);
