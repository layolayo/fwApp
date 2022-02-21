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
$sets = filter_results_to_groups($sets);

$results = [];

// Fetch results
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

function filter_results_to_groups($question_sets)
{
    $database = new Database();
    $conn = $database->connect();
    $stmt = $conn->prepare("SELECT * FROM user_groups WHERE user_email = ?");
    $stmt->bind_param("s", $_SESSION["email"]);
    $stmt->execute();
    $user_groups_count = count($stmt->get_result()->fetch_all());
    if ($user_groups_count > 0) {
        // Get the question sets available to your group
        $stmt = $conn->prepare("SELECT * FROM `user_groups` INNER JOIN group_question_set ON group_question_set.group_id = user_groups.group_id WHERE user_email = ? ");
        $stmt->bind_param("s", $_SESSION["email"]);
        $stmt->execute();
        $groups_result = $stmt->get_result();

        $filtered_results = [];

        // Take only the questions that match the query and are in your available groups
        while ($row = $groups_result->fetch_assoc()) {
            foreach ($question_sets as $r) {
                if ($row["question_set_id"] == $r["ID"]) {
                    $filtered_results[] = $r;
                }
            }
        }
        return $filtered_results;
    } else {
        $unfiltered_results = [];
        foreach ($question_sets as $r) {
            $unfiltered_results[] = $r;
        }
        return $unfiltered_results;
    }
}

// Set content type
header('Content-Type: application/json');
echo json_encode($results);
