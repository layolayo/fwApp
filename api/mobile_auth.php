<?php
include_once '../config/Database.php';
include_once '../model/Facilitator.php';

// Set cors header
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');

$email = $_POST["email"];
$password = $_POST["password"];

if (!isset($email) || !isset($password) ) {
    echo json_encode(["status" => "failed"]);
} else {
    $database = new Database();
    $conn = $database->connect();
    $facilitator = new Facilitator($conn);
    $results = $facilitator->read($email);
    $output = $results->fetch_assoc();

    if (password_verify($password, $output["userpassword"]) == $password) {
        session_start();
        $_SESSION["email"] = $email;
        $_SESSION["authenticated"] = "authenticated";
        if ($output["admin"] == 1) {
            $_SESSION["admin"] = "admin";
        }

        // Set content type
        header('Content-Type: application/json');
        echo json_encode(["status" => "ok", "token" => $_COOKIE["PHPSESSID"]]);
    } else {
        echo json_encode(["status" => "failed"]);
    }
}

