<?php

include_once '../config/Database.php';
include_once '../model/Facilitator.php';

session_start();

// Set cors header
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

$userEmail = $_GET["email"];
$userPassword = $_GET["password"];

$facilitator = new Facilitator();

if ($facilitator->update($userEmail, password_hash($userPassword, PASSWORD_DEFAULT))) {
    $results = $facilitator->read($userEmail);
    $output = $results->fetch_assoc();
    if (password_verify($userPassword, $output["userpassword"]) == $userPassword) {
        //TODO: should be json
        echo "success";
    }
}
echo json_encode(["status" => "failed"]);
