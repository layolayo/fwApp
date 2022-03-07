<?php
/// Handler for retrieving a list of all facilitators

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

// Connect to db
$database = new Database();
$conn = $database->connect();
$stmt = $conn->prepare("SELECT * from facilitator ORDER BY email ASC");
$stmt->execute();
$results = $stmt->get_result();

// Set content type
header('Content-Type: application/json');

$output = array();
while ($row = $results->fetch_assoc()) {
    $group_output = array();
    $group_output["facilitator"] = $row;

    // Load question sets for this group
    $stmt = $conn->prepare("SELECT * FROM user_groups INNER JOIN groups ON user_groups.group_id = groups.id WHERE user_email = ? ORDER BY user_groups.id ASC");
    $stmt->bind_param("s", $row["email"]);
    $stmt->execute();
    $results_qs = $stmt->get_result();
    $group_qs = array();
    while ($row = $results_qs->fetch_assoc()) {
        $group_qs[] = $row;
    }
    $group_output["groups"] = $group_qs;

    $output[] = $group_output;
}
echo json_encode($output);
