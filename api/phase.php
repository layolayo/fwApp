<?php
/// Handler for retrieving a list of all phases

include_once '../config/Database.php';
include_once '../model/Phase.php';

session_start();

// Set cors header
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Credentials: true');

// Ensure that the requester is actually authenticated
if (!array_key_exists("authenticated", $_SESSION ?? []) || $_SESSION["authenticated"] !==  "authenticated") {
//    die("Not authenticated");
}

// Connect to db
$phase = new Phase();
$results = $phase->read();

if(!$results) {
    die("Failed to get phases");
}

// Set content type
header('Content-Type: application/json');

$output = array();
while ($row = $results->fetch_assoc()) {
    $output[] = $row;
}
echo json_encode($output);
