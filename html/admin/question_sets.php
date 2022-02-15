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

if (($_GET["mode"] ?? "") == "add_background_audio") {
    // Handle audio file upload, if a file is given
    if (array_key_exists("fileToUpload", $_FILES)) {
        $question_set_id = $_GET["id"];
        $fileName = $_FILES['fileToUpload']['name'];
        $fileExtension = strtolower(end(explode('.', $fileName)));
        $fileTmpName = $_FILES['fileToUpload']['tmp_name'];

        if ($fileExtension !== "mp3") {
            echo "Upload failed, must be a .mp3";
        } else {
            $target_file = "/kunden/homepages/4/d475696686/htdocs/uniquechange/fwApp/audio-store/" . $_GET["audio"] . ".mp3";
            if (move_uploaded_file($fileTmpName, $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
                $stmt = $conn->prepare("UPDATE `question_set` SET background_audio = ? WHERE question_set.ID = ?");
                $stmt->bind_param("ss", $_GET["audio"], $question_set_id);
                $stmt->execute();
                echo "set background_audio=" . $_GET["audio"] . " for id=" . $question_set_id;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}


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
        <th scope="col">Background Audio</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($question_sets as $q) {
      $background_audio = $q["background_audio"] ?? guidv4();
    ?>
        <tr>
          <td scope='row'><a href='./questions.php?id=<?php echo $q["ID"];?>'><?php echo $q["ID"];?></a></td>
          <td><?php echo $q["specialism"];?></td>
          <td><?php echo $q["type"];?></td>
          <td><?php echo $q["frequency"];?></td>
          <td><?php echo $q["acknowledgements"];?></td>
          <td><?php echo $q["academicSupport"];?></td>
          <td><?php echo $q["title"];?></td>
          <td><?php echo $q["preparation"];?></td>
          <td><?php echo $q["random"];?></td>
          <td><?php echo $q["background"];?></td>
          <td>
            <form action="question_sets.php?mode=add_background_audio&id=<?php echo $q["ID"]; ?>&audio=<?php echo $background_audio; ?>" method="post" enctype="multipart/form-data">
              Select sound to upload:
              <input type="file" name="fileToUpload" id="fileToUpload">
              <input type="submit" value="Upload MP3" name="submit">
            </form>
              <?php
              if($q["background_audio"] == null) {
              ?>
                <p>No background audio added yet</p>
              <?php
              } else {
              ?>
                <a href="/fwApp/audio-store/<?php echo $background_audio; ?>.mp3"><?php echo $q["background_audio"] ?> </a>
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
