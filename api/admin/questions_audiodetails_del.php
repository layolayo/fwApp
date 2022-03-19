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

$question_id = $_GET["id"];

// Connect to db
$database = new Database();
$conn = $database->connect();

$stmt = $conn->prepare("SELECT audio_details FROM `question` WHERE question.ID = ?");
$stmt->bind_param("s", $question_id);
$stmt->execute();
$audio = $stmt->get_result()->fetch_assoc()["audio"];

$stmt = $conn->prepare("UPDATE `question` SET audio_details = NULL WHERE question.ID = ?");
$stmt->bind_param("s", $question_id);
$stmt->execute();

$target_file = "/kunden/homepages/4/d475696686/htdocs/uniquechange/fwApp/audio-store/" . $_GET["audio"] . ".mp3";
unlink($target_file);

// Set content type
echo json_encode(["status" => "ok"]);
