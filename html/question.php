<?php
// Start the session
session_start();

// Echo session variables that were set on previous page
if (!array_key_exists("authenticated", $_SESSION) || $_SESSION["authenticated"] !==  "authenticated") {
    header("Location: login.html");
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">

  <title>Question</title>
    <meta name="description" content="home page">
    <meta name="keywords" content="writing author book facilitated ">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="../css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="../js/bootstrap/bootstrap.min.js"></script>
    <link href="../css/nav.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
  
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../phase/">Phase <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../about/">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../account/">Account</a>
                </li>
                </ul>
                <form class="form-inline my-2 my-lg-0 dropdown">
                    <input class="form-control mr-sm-2 " type="search" id="search" placeholder="Search" aria-label="Search">
                    <ul class="dropdown-menu" id="result">
                    </ul>
                </form>
            </div>
    </nav>
    
    <div class="container" >
        <div id = 'questions' class="list-group mw-100">
                <?php 

                $id = htmlspecialchars($_GET["id"]);
                $max = 0;
                $file = "http://uniquechange.com/fwApp/api/Request.php/question/?id=$id";
                $content = file_get_contents($file);
                $data = json_decode($content, true);
                $name = $data[0]['title'];
                $scaffold = array();

                if ($content) {
                    foreach($data as $qs) {
                        $name = $qs["title"];
                        $random = $qs["random"];
                        $acknowledgements = $qs["acknowledgements"];
                        $background = $qs["background"];
                        $academicSupport = $qs["academicSupport"];
                    }
                }

                echo "<div class= 'text-center'>";
                echo "<br>";
                echo "<h1> $id | $name </h1>";
                if (!empty(str_replace(' ', '', $background ?? ""))) {
                    echo "<a class='btn' data-toggle='collapse' href='#background' role='button' aria-expanded='false' aria-controls='background'>
                            ❯ Background 
                          </a>";
                    echo "<div class='collapse'id='background'>";
                    echo "<p class='lead'> $background </p>";
                    echo "</div>";
                } 

                if (!empty(str_replace(' ', '', $acknowledgements ?? ""))) {
                    echo "<a class='btn' data-toggle='collapse' href='#acknowledgement' role='button' aria-expanded='false' aria-controls='acknowledgement'>
                             ❯ Acknowledgements 
                          </a>";
                    echo "<div class='collapse' id='acknowledgement'>";
                    echo "<p class='lead'> $acknowledgements </p>";
                    echo "</div>";
                }
                
                if (!empty(str_replace(' ', '', $academicSupport ?? ""))) {
                    echo "<a class='btn' data-toggle='collapse' href='#academicSupport' role='button' aria-expanded='false' aria-controls='academicSupport'> 
                            ❯ Academic Support
                          </a>";
                    echo "<div class='collapse' id='academicSupport'>";
                    echo "<p class='lead'>$academicSupport</p>";
                    echo "</div>";
                }
                echo "</div>";

                echo "<div class='row justify-content-md-center'><div class='w-25 alert alert-success col col-lg-2 text-center' role='alert' id='alert' onmouseout='oldText()' style='visibility:hidden'> Copy to clipboard</div></div>";
                if ($random == 1) {
                    echo "<a class='btn btn-secondary' id='random-btn' onclick='random()'> Randomise! </a>";
                }

                $file = "http://uniquechange.com/fwApp/api/Request.php/questions/?id=$id";
                $content = file_get_contents($file);
                $data = json_decode($content, true);

                echo "<div class='row'>";

                
                echo "<ul class='col-md-8 order-md-1'>";
                echo "<p class='lead'> Questions </p>";
                if ($content) {
                    $fullScaffhold = Array();
                    foreach($data as $title) {
                        if (!empty($title)) {
                            $id = $title["ID"];
                            $details = $title["details"];
                            $question = $title["question"];
                            $repeat = $title["repeats"];
                            $scaffold = $title["scaffold"];
                            
                            if ($repeat || $repeat > 0) {
                                for ($x = 0; $x <= $repeat; $x++) {
                                    $fullScaffhold = array_merge($fullScaffhold, questions($id, $details, $question, $scaffold, $max));
                                    $max += 1;
                                }

                            } else {
                                $fullScaffhold = array_merge($fullScaffhold, questions($id, $details, $question, $scaffold, $max));
                                $max += 1;
                                }
                        }
                    }
                }
                echo "</ul>";
                

                echo "<div class='col-md-4 order-md-2 mb-4'>";
                echo "<p class='lead'> Full  Scaffold </p>";
                echo "<textarea onclick='copy(this)' onmouseout='oldText()'>" .implode("\n", $fullScaffhold). "</textarea>";
                echo "</div>";

                echo "<input id = 'hidden-input' type='hidden' value='$max'>";

                echo "</div>";
                
                function questions($id, $details, $question, $scaffhold, $max) {
                    $scaffholdArray = [];
                    echo "<li class='list-group-item' onclick=selectLi($max) 'id='$max' > $id. $question";
                    echo "<br>";
                    if (!empty(trim($details ?? "")) || $details ) {
                        echo "<br>";
                        echo "<button  style='font-size:0.5rem;' class='btn btn-outline-secondary' data-toggle='collapse' data-target='#myInput$max' role='button' aria-expanded='false' aria-controls='myInput$max'>
                                ❯ </button>";
                        echo "<br>";
                        echo "<p class='collapse' id = 'myInput$max'>";
                        echo "<br>";
                        echo "<span class='card card-body bg-light' style='font-size:0.7rem;'> $details </span>";
                        echo "</p>";
                    }
                    echo "<br>";
                    if (!empty(trim($scaffhold ?? "")) || $scaffhold ) {
                        echo "<textarea onmouseout='oldText()' onclick='copy(this)' >" .$scaffhold. "</textarea>";
                        array_push($scaffholdArray, $scaffhold);
                    }
                    echo "</li>";

                    return $scaffholdArray;
                }

            ?>
        </div>
    </div>
    <div class="d-flex justify-content-center">
      <button type="button" onclick='update_frequency()' class="m-5 btn btn-success">Done</button>
    </div>
    <br>

    <footer class="pagination navbar navbar-dark bg-dark footer mt-auto py-3" style="position: -webkit-sticky;">
      <div class="container">
        <a class="btn btn-outline-secondary text-white" id="back-btn" onclick="back()"> <i class="bi-arrow-up"></i> </a>
        <a class="btn btn-outline-secondary text-white" id="next-btn" onclick="next()"> <i class="bi-arrow-down"></i> </a>
      </div>
    </footer>
    <script src="../../js/pagination.js"></script>
    <script src="../../js/search.js"> </script>

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
            xhttp.open("GET", "http://uniquechange.com/fwApp/api/Request.php/frequency/?id="+id);
            xhttp.send();
        }
    </script>
    
</body>
</html>
