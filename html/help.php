<?php
// Start the session
session_start();

// Ensure that the user is logged in
if (!array_key_exists("authenticated", $_SESSION) || $_SESSION["authenticated"] !==  "authenticated") {
    header("Location: /fwApp/html/login.html");
}

require_once '../config/Database.php';
require_once __DIR__ . '/../vendor/erusev/parsedown/Parsedown.php';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Help</title>
  <meta name="description" content="help page">
  <meta name="keywords" content="writing author book facilitated ">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script src="/fwApp/js/jquery/jquery.min.js"></script>
  <link href="/fwApp/css/bootstrap-5.1/bootstrap.min.css" rel="stylesheet">
  <script src="/fwApp/js/bootstrap-5.1/bootstrap.bundle.min.js"></script>
  <link href="/fwApp/css/nav.css" rel="stylesheet">
  <script src="/fwApp/js/search.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="/fwApp/html/phase.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/fwApp/html/help.php">Help</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/fwApp/html/account.php">Account</a>
        </li>
      </ul>
      <form class="nav-item my-2 my-lg-0 dropdown">
        <input class="form-control me-2" type="search" id="search" placeholder="Search" aria-label="Search">
        <ul class="dropdown-menu" id="result">
        </ul>
      </form>
    </div>
  </div>
</nav>

<div class="container p-2">
  <h1>Help page</h1>
    <?php
    // Read md file
    $myfile = fopen("../help.md", "r") or die("Unable to open file!");
    $file_content = fread($myfile,filesize("../help.md"));
    fclose($myfile);

    echo \Parsedown::instance()
        ->setSafeMode(false)
        ->setUrlsLinked(true)
        ->setMarkupEscaped(false)
        ->setBreaksEnabled(true)
        ->text($file_content);
    ?>
</div>
</body>
</html>
