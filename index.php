<?php

include_once 'config/Database.php';
include_once 'model/QuestionSet.php';
include_once 'model/Category.php';

$database = new Database();
$conn = $database->connect();
$test_query = "SELECT * FROM facilitator";
$table = "facilitator";
$email = "richard.dyter@worldenglishagency.com";
$password = 
$email = "'$email'";
$query = "SELECT id, email, userpassword, disableuser, superuser FROM ". $table." WHERE email = ".$email;
$result = $conn->query($query) or die("error: " . mysqli_error($conn));
$row = $result->fetch_assoc();
if (password_verify("flower", $row["userpassword"]) == "flower") {
    echo "true";
}


$tblCnt = 0;
while($tbl = $result->fetch_assoc()) {
$tblCnt++;
}

if (!$tblCnt) {
echo "There are no tables\n";
} else {
echo "There are $tblCnt tables\n";
}


$question = new QuestionSet($conn);
$question = $question->read_categoried_question("Main course");
while ($row = $question->fetch_assoc()) {
    echo $row["title"];
}
echo print_r(array_values($question->fetch_assoc()));

?>

