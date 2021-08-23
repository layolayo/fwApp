<?php
include_once '../config/Database.php';
include_once '../model/Facilitator.php';


$email = $_POST["inputEmail"];
$password = $_POST["inputPassword"];

function auth($email, $password) {
    if (isset($email) && isset($password) ) {
        $database = new Database();
        $conn = $database->connect();
        $facilitator = new Facilitator($conn);
        $results = $facilitator->read($email);
        $output = $results->fetch_assoc();
        if (password_verify($password, $output["userpassword"]) == $password) {
            return true;
        }
    }
        return false;
}

if (auth($email, $password)) {
    session_start();
    $_SESSION["email"] = $email;
    $_SESSION["authenticated"] = "authenticated";
    if (isset($_SESSION["email"]) && isset($_SESSION["authenticated"])) {
        header("Location: ../html/phase.php/");
    }
} 
    
?>