<?php
/// Handler for retrieving a list of all phases

include_once '../../config/Database.php';

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
header('Content-Type: application/json');


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

$group_id = $_GET["gid"] ?? die("No group id");
$question_set_id = $_GET["qsid"] ?? die("No question set id");

// Connect to db
$database = new Database();
$conn = $database->connect();
$stmt = $conn->prepare("INSERT INTO group_question_set (id, group_id, question_set_id) VALUES (NULL, ?, ?)");
$stmt->bind_param("ss", $group_id, $question_set_id);
$stmt->execute();
$results = $stmt->get_result();

// Set content type
header('Content-Type: application/json');


echo json_encode(["status" => "ok"]);
