<?php
/// Handler for deleting a group

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

$group_id = $_GET["gid"] ?? die("No group id");
$user_email = $_GET["email"] ?? die("No email");

// Connect to db
$database = new Database();
$conn = $database->connect();
$stmt = $conn->prepare("DELETE FROM user_groups WHERE user_email=? AND group_id=?");
$stmt->bind_param("ss", $user_email, $group_id);
$stmt->execute();
$results = $stmt->get_result();

// Set content type
header('Content-Type: application/json');


echo json_encode(["status" => "ok"]);
