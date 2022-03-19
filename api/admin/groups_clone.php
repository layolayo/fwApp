<?php
/// Handler for cloning a group

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

$new_group_name = $_GET["name"] ?? die("No group name");
$old_group_id = $_GET["ogid"] ?? die("No group id");

// Connect to db
$database = new Database();
$conn = $database->connect();

// Create the new group
$stmt = $conn->prepare("INSERT INTO groups (id, name) VALUES (NULL, ?)");
$stmt->bind_param("s", $new_group_name);
$stmt->execute();
$results = $stmt->get_result();

// Get the id of the new group
$stmt = $conn->prepare("SELECT * FROM groups WHERE name = ?");
$stmt->bind_param("s", $new_group_name);
$stmt->execute();
$results = $stmt->get_result();

$new_group_id = $results->fetch_assoc()["id"];

//Get all the group ids from the old group
$stmt = $conn->prepare("SELECT * FROM group_question_set WHERE group_id = ?");
$stmt->bind_param("s", $old_group_id);
$stmt->execute();
$results = $stmt->get_result();

// Insert all the old ids under the new group
while ($row = $results->fetch_assoc()) {
    $stmt = $conn->prepare("INSERT INTO group_question_set (id, group_id, question_set_id) VALUES (NULL, ?, ?)");
    $stmt->bind_param("ss", $new_group_id, $row["question_set_id"]);
    $stmt->execute();
}

// Set content type
header('Content-Type: application/json');

echo json_encode(["status" => "ok"]);
