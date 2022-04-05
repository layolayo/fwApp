<?php
include_once '../config/Database.php';
include_once '../model/Facilitator.php';


$email = $_POST["inputEmail"];
$password = $_POST["inputPassword"];


if (!isset($email) || !isset($password) ) {
    header("Location: /fwApp/html/login.html");
}

$database = new Database();
$conn = $database->connect();
$facilitator = new Facilitator($conn);
$results = $facilitator->read($email);
$output = $results->fetch_assoc();

if (password_verify($password, $output["userpassword"]) == $password) {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    session_start();
    $_SESSION["email"] = $email;
    $_SESSION["authenticated"] = "authenticated";
    if($output["admin"] == 1) {
        $_SESSION["admin"] = "admin";
    } else {
        $_SESSION["admin"] = null;
    }

    if($output["developer"] == 1) {
        $_SESSION["developer"] = "developer";
    } else {
        $_SESSION["developer"] = null;
    }

    if (isset($_SESSION["email"]) && isset($_SESSION["authenticated"])) {
        header("Location: /fwApp/html/phase.php");
    }
} else {
    header("Location: /fwApp/html/login.html");
}

