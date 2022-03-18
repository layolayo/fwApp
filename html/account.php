<?php
// Start the session
session_start();

// Ensure that the user is logged in
if (!array_key_exists("authenticated", $_SESSION) || $_SESSION["authenticated"] !==  "authenticated") {
    header("Location: /fwApp/html/login.html");
}

include_once '../config/Database.php';
include_once '../model/Facilitator.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>User</title>
    <meta name="description" content="home page">
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
        <ul class="navbar-nav me-5 mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="/fwApp/html/phase.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/fwApp/html/help.php">Help</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/fwApp/html/account.php">Account</a>
          </li>
            <?php
            if (array_key_exists("admin", $_SESSION) && $_SESSION["admin"] === "admin") {
                ?>
              <li class="nav-item">
                <a class="nav-link" href="/fwApp/html/admin/">ADMIN</a>
              </li>
                <?php
            }
            ?>
        </ul>
      </div>
      <form class="nav-item my-2 my-lg-0 dropdown" style="width: 33%; margin-right: 33%">
        <input class="form-control me-5" type="search" id="search" placeholder="Search" aria-label="Search">
        <ul class="dropdown-menu" style="width: 100%" id="result">
        </ul>
      </form>
    </div>
  </nav>

    <div class="container p-2">
      <h1>Welcome <?php echo $_SESSION["email"] ?></h1>
      <a class="btn btn-secondary m-2" href="/fwApp/html/logout.php">Logout</a>
      <ul class="list-group m-2">
        <?php
        $question = new Facilitator();
        $results = $question->getUserQs($_SESSION["email"]);
        while ($row = $results->fetch_assoc()) {
            $id = $row["ID"];
            $title = $row["title"];
        ?>
          <li class="list-group-item"><a href="/fwApp/html/question.php?id=<?php echo $id; ?>"><?php echo $id; ?>) <?php echo $title; ?> </a></li>
        <?php
        }
        ?>
      </ul>
    </div>
  </body>
</html>
