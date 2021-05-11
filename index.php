<?php

include_once 'config/Database.php';

$database = new Database();
$conn = $database->connect();
$test_query = "SHOW TABLES FROM dbs1652193";
$result = $conn->query($test_query) or die(mysqli_error());

$tblCnt = 0;
while($tbl = $result->fetch_assoc()) {
$tblCnt++;
}

if (!$tblCnt) {
echo "There are no tables\n";
} else {
echo "There are $tblCnt tables\n";
}
?>
