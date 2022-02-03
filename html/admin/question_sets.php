<?php
session_start();

// User must be logged in
if (!array_key_exists("authenticated", $_SESSION) || $_SESSION["authenticated"] !==  "authenticated") {
    header("Location: /fwApp/html/login.html");
}

// User must be an admin
if (!array_key_exists("admin", $_SESSION) || $_SESSION["admin"] !== "admin") {
    header("Location: /fwApp/html/login.html");
}

include_once '../../config/Database.php';
include_once '../../model/QuestionSet.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <title>FWAPP - Admin</title>
    <link href="../../css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="../../js/bootstrap/bootstrap.min.js"></script>
</head>

<body>

<?php
    $database = new Database();
    $conn = $database->connect();

    $stmt = $conn->prepare("SELECT * FROM question_set");
    $stmt->execute();
    $question_sets = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Specialism</th>
        <th scope="col">Type</th>
        <th scope="col">Frequency</th>
        <th scope="col">Acknowledgements</th>
        <th scope="col">AcademicSupport</th>
        <th scope="col">Title</th>
        <th scope="col">Preparation</th>
        <th scope="col">Random</th>
        <th scope="col">Background</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($question_sets as $q) {
        echo "<tr>";
        echo "<td scope='row'><a href='./questions.php?id=" . $q["ID"] . "'>" . $q["ID"] . "</a></td>";
        echo "<td>" . $q["specialism"] . "</td>";
        echo "<td>" . $q["type"] . "</td>";
        echo "<td>" . $q["frequency"] . "</td>";
        echo "<td>" . $q["acknowledgements"] . "</td>";
        echo "<td>" . $q["academicSupport"] . "</td>";
        echo "<td>" . $q["title"] . "</td>";
        echo "<td>" . $q["preparation"] . "</td>";
        echo "<td>" . $q["random"] . "</td>";
        echo "<td>" . $q["background"] . "</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>
</body>
