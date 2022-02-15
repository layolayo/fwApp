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

    <title>FWAPP - Admin:Groups</title>
    <link href="../../css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="../../js/bootstrap/bootstrap.min.js"></script>
</head>

<body>

<?php

function guidv4()
{
    $data = $data ?? random_bytes(16);

    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

    $database = new Database();
    $conn = $database->connect();

if (($_GET["mode"] ?? "") == "add_group") {
    $stmt = $conn->prepare("INSERT INTO `groups` (`name`) VALUES (?)");
    $stmt->bind_param("s", $_POST["groupName"]);
    $stmt->execute();
    echo $stmt->error;
}


    $stmt = $conn->prepare("SELECT * FROM groups");
    $stmt->execute();
    $groups = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($groups as $q) {
    ?>
        <tr>
          <td scope='row'><?php echo $q["id"];?></td>
          <td><?php echo $q["name"];?></td>
        </tr>
    <?php
    }
    ?>
    </tbody>
</table>

  <form method="post" action="./groups.php?mode=add_group">
    <label for="groupName">Group Name:</label><input type="text" id="groupName" name="groupName">
    <input type="submit" value="Create Group" name="submit">
  </form>
</body>
