<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once '../config/Database.php';
include_once '../model/Phase.php';

$database = new Database();
$conn = $database->connect();

$phase = new Phase($conn);
$results = $phase->read_phase_titles();
$output = array();
while ($row = $results->fetch_assoc()) {
    $output[] = $row;
}
echo json_encode($output);

?>