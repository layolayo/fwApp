<?php

include_once '../config/Database.php';
include_once '../model/Token.php';
include_once '../model/Facilitator.php';

session_start();

// Set cors header
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

$userEmail = $_GET["email"];
$userToken = $_GET["token"];

$token = new Token();
$facilitator = new Facilitator();

$resultsToken = $token->read($userToken);
$resultsEmail = $facilitator->read($userEmail);
$outputToken = $resultsToken->fetch_assoc();
$outputEmail = $resultsEmail->fetch_assoc();

if ($outputToken && $outputEmail ) {
    echo "success";
} else {
    echo "failed";
}
