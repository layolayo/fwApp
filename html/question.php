<?php
// Start the session
session_start();

// Ensure that the user is logged in
if (!array_key_exists("authenticated", $_SESSION) || $_SESSION["authenticated"] !==  "authenticated") {
    header("Location: /fwApp/html/login.html");
}

include_once '../config/Database.php';
include_once '../model/QuestionSet.php';
?>

<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">

    <title>FWAPP - Question</title>
    <meta name="description" content="home page">
    <meta name="keywords" content="writing author book facilitated ">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="/fwApp/js/jquery/jquery.min.js"></script>
    <link href="/fwApp/css/bootstrap-5.1/bootstrap.min.css" rel="stylesheet">
    <script src="/fwApp/js/bootstrap-5.1/bootstrap.bundle.min.js"></script>
    <link href="/fwApp/css/nav.css" rel="stylesheet">
    <link rel="stylesheet" href="/fwApp/css/bootstrap-icons/bootstrap-icons.css">
    <script src="/fwApp/js/pagination.js"></script>
    <script src="/fwApp/js/search.js"></script>
    <script>
      function update_frequency() {
          const xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function() {
              if(xhttp.readyState === XMLHttpRequest.DONE) {
                  document.location = "/fwApp/html/phase.php/";
              }
          };
          window.$_GET = new URLSearchParams(location.search);
          const id = $_GET.get('id');
          xhttp.open("GET", "/fwApp/api/frequency.php?id="+id);
          xhttp.send();
      }
    </script>
  </head>
  <body class="d-flex flex-column h-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/fwApp/html/phase.php">Phase</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/fwApp/html/about.php">About</a>
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

  <main class="flex-shrink-0">
    <div class="container">
      <div id='questions' class="list-group mw-100">
        <?php
          $question = new QuestionSet();
          $results = $question->a_question_set($_GET["id"]);
          $results = $results->fetch_assoc();
        ?>
        <div class="text-center mt-4">
          <h1> <?php echo htmlspecialchars($_GET["id"]); ?> | <?php echo $results["title"]; ?> </h1>
          <?php
          if(!empty(trim($results["background"] ?? ""))) {
          ?>
          <a class="btn link-primary" data-bs-toggle="collapse" href="#background" role="button" aria-expanded="false" aria-controls="background">
            ❯ Background
          </a>
          <div class="collapse" id="background">
            <p class="lead"> <?php echo $results["background"]; ?> </p>
          </div>
          <?php
          }
          ?>

          <?php
          if(!empty(trim($results["acknowledgements"] ?? ""))) {
          ?>
          <a class="btn link-primary" data-bs-toggle="collapse" href="#acknowledgement" role="button" aria-expanded="false" aria-controls="acknowledgement">
            ❯ Acknowledgements
          </a>
          <div class="collapse" id="acknowledgement">
            <p class="lead"> <?php echo $results["acknowledgements"]; ?> </p>
          </div>
          <?php
          }
          ?>

          <?php
          if(!empty(trim($results["academicSupport"] ?? ""))) {
          ?>
          <a class="btn link-primary" data-bs-toggle="collapse" href="#academicSupport" role="button" aria-expanded="false" aria-controls="academicSupport">
            ❯ Academic Support
          </a>
          <div class="collapse" id="academicSupport">
            <p class="lead" > <?php echo $results["academicSupport"]; ?> </p>
          </div>
          <?php
          }
          ?>
        </div>
        <div class='row justify-content-md-center'>
          <div class='w-25 alert alert-success col col-lg-2 text-center' role='alert' id='alert' onmouseout='oldText()' style='visibility:hidden'> Copy to clipboard</div>
        </div>
        <?php
        if(!empty(trim($results["random"] ?? ""))) {
        ?>
        <a class='btn btn-secondary' id='random-btn' onclick='random()'> Randomise! </a>
        <?php
        }
        ?>

        <?php
        $results = $question->questions($_GET["id"]);
        $output = array();
        while ($row = $results->fetch_assoc()) {
            $output[] = $row;
        }
        ?>
        <div class='row'>
          <ul class='col-md-8 order-md-1'>
            <p class='lead'> Questions </p>
              <?php
              $fullScaffold = Array();
              $max = 0;
              $index = 0;
              foreach ($output as $q) {
                  $id = $q["ID"];
                  $details = $q["details"];
                  $question = $q["question"];
                  $repeat = $q["repeats"];
                  $scaffold = $q["scaffold"];
                  $audio = $q["audio"];
                  $audio_path = "/fwApp/audio-store/" . $audio . ".mp3";

                  if(!empty($scaffold)) {
                      $fullScaffold[] = $scaffold;
                  }
                  $count = 1;
                  if($repeat || $repeat > 0) {
                      $count = $repeat + 1;
                  }
                  $max += $count;

                  for($i = 0; $i < $count; $i++) {
                    ?>
                    <li class='list-group-item' onclick=selectLi(<?php echo $index; ?>) id='<?php echo $index; ?>'> <?php echo $question; ?>
                    <br/>
                    <?php
                    if (!empty(trim($details ?? "")) || $details ) {
                    ?>
                      <br/>
                      <button style="font-size:0.5rem;" class="btn btn-outline-secondary" data-bs-toggle="collapse" href="#details-<?php echo $index; ?>" role="button" aria-expanded="false" aria-controls="details-<?php echo $index; ?>">❯</button>
                      <br/>
                      <div class="collapse" id="details-<?php echo $index; ?>">
                        <br/>
                        <span class="card card-body bg-light" style="font-size:0.7rem;"><?php echo $details; ?></span>
                      </div>
                    <?php
                    }
                    ?>
                    <br/>
                    <?php
                    if(!empty($audio ?? "")) {
                    ?>
                      <audio controls>
                        <source src="<?php echo $audio_path; ?>" type="audio/mpeg"/>
                      </audio>
                    <?php
                    }
                    ?>
                    <br/>
                    <?php
                    if (!empty(trim($scaffhold ?? "")) || $scaffold ) {
                    ?>
                      <textarea onmouseout='oldText()' onclick='copy(this)' ></textarea>
                    <?php
                    }
                    ?>
                    </li>
                    <?php
                      $index += 1;
                  }
              }
              ?>
          </ul>
          <div class='col-md-4 order-md-2 mb-4'>
            <p class='lead'> Full  Scaffold </p>
            <textarea onclick='copy(this)' onmouseout='oldText()'><?php echo implode("\n", $fullScaffold);?></textarea>
          </div>
          <input id = 'hidden-input' type='hidden' value="<?php echo $max; ?>">
        </div>
        </div>
      <div class="d-flex justify-content-center">
        <button type="button" onclick='update_frequency()' class="m-5 btn btn-success">Done</button>
      </div>
    </div>
  </main>

  <footer class="pagination navbar navbar-dark bg-dark footer mt-auto py-3" style="position: -webkit-sticky;">
    <div class="container">
      <a class="btn btn-outline-secondary text-white" id="back-btn" onclick="back()"> <i class="bi-arrow-up"></i> </a>
      <a class="btn btn-outline-secondary text-white" id="next-btn" onclick="next()"> <i class="bi-arrow-down"></i> </a>
    </div>
  </footer>
</body>
</html>
