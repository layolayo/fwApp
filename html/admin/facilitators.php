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

    <title>FWAPP - Admin:Facilitators</title>
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
    $stmt = $conn->prepare("INSERT INTO `user_groups` (`user_email`, `group_id`) VALUES (?, ?)");
    $stmt->bind_param("si", $_GET["email"], $_POST["groupId"]);
    $stmt->execute();
    echo $stmt->error;
}


    $stmt = $conn->prepare("SELECT * FROM facilitator");
    $stmt->execute();
    $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">Email</th>
        <th scope="col">Disabled</th>
        <th scope="col">Superuser</th>
        <th scope="col">Admin</th>
        <th scope="col">Developer</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($users as $q) {
        $stmt = $conn->prepare("SELECT groups.name AS group_name FROM user_groups INNER JOIN groups ON groups.id = user_groups.group_id WHERE user_groups.user_email = ?");
        $stmt->bind_param("s", $q["email"]);
        $stmt->execute();
        $user_groups = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    ?>
        <tr>
          <td><?php echo $q["email"];?></td>
          <td><?php echo $q["disableuser"];?></td>
          <td><?php echo $q["superuser"];?></td>
          <td><?php echo $q["admin"];?></td>
          <td><?php echo $q["developer"];?></td>
          <td>
            <p>Add group</p>
            <form method="post" action="./facilitators.php?mode=add_group&email=<?php echo $q["email"];?>">
              <label for="groupId">Id:</label><input type="number" id="groupId" name="groupId">
              <input type="submit" value="Add" name="submit">
            </form>
            <p>Groups</p>
              <?php
              foreach ($user_groups as $qs) {
              ?>
                <p><?php echo $qs["group_name"];?></p>
              <?php
              }
              ?>
          </td>
        </tr>
    <?php
    }
    ?>
    </tbody>
</table>
</body>
