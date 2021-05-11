<?php

// headers

header('Acess-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../model/Facilitator.php';

$database = new Database();
$db = $database->connect();

// get number of facilator.
$facilator = new Facilitator($db);
//$result = $faciltator->read();
$count = $result->rowCount();
//get the number if users


//echo $result->fetch_assoc();





?>