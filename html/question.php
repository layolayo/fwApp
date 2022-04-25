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

    <script
            src="https://browser.sentry-cdn.com/6.18.2/bundle.min.js"
            integrity="sha384-hxcWlK1seT59Ftk+5StsgedF3GKBtJGRKWf6YgKV8FJzYpTJHgVc/IBzleXnfYDI"
            crossorigin="anonymous"
    ></script>
    <script
            src="https://browser.sentry-cdn.com/6.18.2/bundle.tracing.min.js"
            integrity="sha384-mAvo+boV/DuDB7oEhXJlhWaxExqvniNXXZxhMk8Mp42k+1J6NbPlCbMHFis/KN2Y"
            crossorigin="anonymous"
    ></script>
    <script>
        Sentry.init({
            dsn: "https://c474b8e331584729b06eb608ac43c9b6@o1155143.ingest.sentry.io/6255121",
            integrations: [new Sentry.BrowserTracing()],
            // We recommend adjusting this value in production, or using tracesSampler
            // for finer control
            tracesSampleRate: 1.0,
        });
    </script>

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
        <ul class="navbar-nav me-5 mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/fwApp/html/phase.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/fwApp/html/help.php">Help</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/fwApp/html/account.php">Account</a>
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
              <?php
              if(!empty(trim($results["background_audio"] ?? ""))) {
                  $background_audio_path = "/fwApp/audio-store/" . $results["background_audio"] . ".mp3";
                  ?>
                <audio controls>
                  <source src="<?php echo $background_audio_path; ?>" type="audio/mpeg"/>
                </audio>
              <?php
              }
              ?>
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

          <?php
          if(!empty(trim($results["preparation"] ?? ""))) {
          ?>
          <a class="btn link-primary" data-bs-toggle="collapse" href="#preparation" role="button" aria-expanded="false" aria-controls="preparation">
            ❯ Preparation
          </a>
          <div class="collapse" id="preparation">
            <p class="lead" > <?php echo $results["preparation"]; ?> </p>
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
              $all_questions = Array();
              $max = 0;
              $index = 0;
              foreach ($output as $q) {
                  $id = $q["ID"];
                  $details = $q["details"];
                  $question = $q["question"];
                  $repeat = $q["repeats"];
                  $audio = $q["audio"];
                  $audio_details = $q["audio_details"];
                  $image = $q["image"];
                  $image_alttext = $q["image_alttext"];
                  $audio_path = "/fwApp/audio-store/" . $audio . ".mp3";
                  $audio_details_path = "/fwApp/audio-store/" . $audio_details . ".mp3";
                  $image_path = "/fwApp/image-store/" . $image . ".png";

                  $count = 1;
                  if($repeat || $repeat > 0) {
                      $count = $repeat + 1;
                  }
                  $max += $count;

                  $all_questions[] = $question;

                  for($i = 0; $i < $count; $i++) {
                    ?>
                    <li class='list-group-item' onclick=selectLi(<?php echo $index; ?>) id='<?php echo $index; ?>'> <?php echo $question; ?>
                      <button class="btn btn-secondary " onclick="copy2('<?php echo str_replace("'", "\'", $question); ?>');"><i class="bi-clipboard"></i></button>
                    <br/>
                    <?php
                    if (!empty(trim($details ?? "")) || $details ) {
                    ?>
                      <br/>
                      <button style="font-size:0.75rem;" class="btn btn-outline-secondary" data-bs-toggle="collapse" href="#details-<?php echo $index; ?>" role="button" aria-expanded="false" aria-controls="details-<?php echo $index; ?>">Sentence Starters:</button>
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
                      <p>Hear the question:</p>
                      <audio controls onplay='selectLi(<?php echo $index; ?>)'>
                        <source src="<?php echo $audio_path; ?>" type="audio/mpeg"/>
                      </audio>
                    <?php
                    }
                    ?>
                    <br/>
                    <?php
                    if(!empty($audio_details ?? "")) {
                    ?>
                      <p>Extra Details:</p>
                      <audio controls onplay='selectLi(<?php echo $index; ?>)'>
                        <source src="<?php echo $audio_details_path; ?>" type="audio/mpeg"/>
                      </audio>
                    <?php
                    }
                    ?>
                    <br/>
                    <?php
                    if(!empty($image ?? "")) {
                    ?>
                    <img style="width: auto; height: 20em;" src="<?php echo $image_path; ?>" alt="<?php echo $image_alttext; ?>"/>
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
            <button class="btn btn-secondary" onclick="copy2('<?php echo str_replace("'", "\'", implode("\\n\\n", $all_questions)); ?>');"><i class="bi-clipboard"></i> Copy All Questions</button>
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
