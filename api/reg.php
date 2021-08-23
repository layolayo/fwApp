<?php
include_once '../config/Database.php';
include_once '../model/Token.php';
include_once '../model/Facilitator.php';



$id = $_POST["token"];
$email = $_POST["inputEmail"];
$password = $_POST["inputPassword"];


function isTokenAccepted($id) {
    if (isset($id) ) {
        $database = new Database();
        $conn = $database->connect();
        $token = new Token($conn);
        $results = $token->read($id);
        $output = $results->fetch_assoc();
        if ($id == $output["ID"]) {
            return true;
        }

    }
        return false;
}

function isRegisterSuccess($email, $password) {
    $database = new Database();
    $conn = $database->connect();
    $facilitator = new Facilitator($conn);
    return $facilitator->insert($email, password_hash($password, PASSWORD_DEFAULT));

}

function isTokenDeleted($id) {
    $database = new Database();
    $conn = $database->connect();
    $token = new Token($conn);
    return $token->delete($id);
}

if (isTokenAccepted($id) && isRegisterSuccess($email, $password) && isTokenDeleted($id)) {
    echo "success";
}
    
?>