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

$question_set_id = $_GET["id"] ?? 0;

$database = new Database();
$conn = $database->connect();

// Handle audio file upload, if a file is given
if(array_key_exists("fileToUpload", $_FILES)) {
    $question_id = $_GET["qid"];
    $fileName = $_FILES['fileToUpload']['name'];
    $fileExtension = strtolower(end(explode('.', $fileName)));
    $fileTmpName = $_FILES['fileToUpload']['tmp_name'];

    if($fileExtension !== "mp3") {
      echo "Upload failed, must be a .mp3";
    } else {
        $target_file = "/kunden/homepages/4/d475696686/htdocs/uniquechange/fwApp/audio-store/" . $_GET["audio"] . ".mp3";
        if (move_uploaded_file($fileTmpName, $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            $stmt = $conn->prepare("UPDATE `question` SET audio = ? WHERE question.ID = ?");
            $stmt->bind_param("ss", $_GET["audio"], $question_id);
            $stmt->execute();
            echo "set audio=" . $_GET["audio"] . " for id=" . $question_id;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

function guidv4()
{
    $data = $data ?? random_bytes(16);

    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
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

    $stmt = $conn->prepare("SELECT * FROM ask, question WHERE ask.question = question.ID and questionSet = ? ORDER BY qOrder ASC");
    $stmt->bind_param("s", $question_set_id);
    $stmt->execute();
    $question_sets = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">question</th>
        <th scope="col">qOrder</th>
        <th scope="col">questionSet</th>
        <th scope="col">repeats</th>
        <th scope="col">scaffold</th>
        <th scope="col">details</th>
        <th scope="col">audio</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($question_sets as $q) {
      $audio = $q["audio"] ?? guidv4();
      ?>
      <tr>
        <td scope='row'> <?php echo $q["ID"] ?> </td>
        <td> <?php echo $q["question"] ?> </td>
        <td> <?php echo $q["qOrder"] ?> </td>
        <td> <?php echo $q["questionSet"] ?> </td>
        <td> <?php echo $q["repeats"] ?> </td>
        <td> <?php echo $q["scaffold"] ?> </td>
        <td> <?php echo $q["details"] ?> </td>
        <td>
          <form action="questions.php?id=<?php echo $question_set_id ?>&audio=<?php echo $audio ?>&qid=<?php echo $q["ID"] ?>" method="post" enctype="multipart/form-data">
            Select sound to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload MP3" name="submit">
          </form>
            <?php
              if($q["audio"] == null) {
            ?>
                <p>No audio added yet</p>
            <?php
              } else {
            ?>
                <a href="/fwApp/audio-store/<?php $audio ?>.mp3"><?php echo $q["audio"] ?> </a>
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


