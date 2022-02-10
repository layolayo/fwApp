<?php
include_once '../config/Database.php';
include_once '../model/Facilitator.php';


$email = $_POST["inputEmail"];
$password = $_POST["inputPassword"];


if (!isset($email) || !isset($password) ) {
    header("Location: login.html");
}

$database = new Database();
$conn = $database->connect();
$facilitator = new Facilitator($conn);
$results = $facilitator->read($email);
$output = $results->fetch_assoc();

if (password_verify($password, $output["userpassword"]) == $password) {
    session_start();
    $_SESSION["email"] = $email;
    $_SESSION["authenticated"] = "authenticated";
    if($output["admin"] == 1) {
        $_SESSION["admin"] = "admin";
    }
    if($output["developer"] == 1) {
        $_SESSION["developer"] = "developer";
    }

    if (isset($_SESSION["email"]) && isset($_SESSION["authenticated"])) {
        header("Location: ../html/phase.php/");
    }
} else {
    header("Location: login.html");
}

