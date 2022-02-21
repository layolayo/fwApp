<?php
// Start the session
session_start();

// Ensure that the user is logged in
if (!array_key_exists("authenticated", $_SESSION) || $_SESSION["authenticated"] !==  "authenticated") {
    header("Location: /fwApp/html/login.html");
}

//require_once __DIR__ . '/../vendor/autoload.php';
require_once '../config/Database.php';
require_once '../model/Phase.php';
require_once '../model/Type.php';
require_once '../model/Specialism.php';
//
//use DebugBar\StandardDebugBar;
//
//$debugbar = new StandardDebugBar();
//$debugbarRenderer = $debugbar->getJavascriptRenderer();
//
//$debugbar["messages"]->addMessage("hello world!");
//"maximebf/debugbar": "1.*"
//?>

<!doctype html>
<html lang="en" class="h-100">

<head>
  <meta charset="utf-8">

  <title>FWAPP</title>
    <style>
        .zoom:hover {
            -ms-transform: scale(1.1);
            /* IE 9 */
            -webkit-transform: scale(1.1);
            /* Safari 3-8 */
            transform: scale(1.1);
            cursor: pointer;
        }
    </style>
    <meta name="description" content="home page">
    <meta name="keywords" content="writing author book facilitated ">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="/fwApp/js/jquery/jquery.min.js"></script>
    <link href="/fwApp/css/bootstrap-5.1/bootstrap.min.css" rel="stylesheet">
    <script src="/fwApp/js/bootstrap-5.1/bootstrap.bundle.min.js"></script>
    <link href="/fwApp/css/nav.css" rel="stylesheet">
    <script src="/fwApp/js/search.js"></script>
    <?php if (!array_key_exists("developer", $_SESSION) || $_SESSION["developer"] !==  "developer") {
//        echo $debugbarRenderer->renderHead();
    }?>
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
          <a class="nav-link active" aria-current="page" href="/fwApp/html/phase.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/fwApp/html/help.php">Help</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/fwApp/html/account.php">Account</a>
        </li>
          <?php
          if (!array_key_exists("admin", $_SESSION) || $_SESSION["admin"] === "admin") {
          ?>
          <li class="nav-item">
            <a class="nav-link" href="/fwApp/html/admin/question_sets.php">ADMIN</a>
          </li>
          <?php
          }
          ?>
      </ul>
      <form class="nav-item my-2 my-lg-0 dropdown">
        <input class="form-control me-2" type="search" id="search" placeholder="Search" aria-label="Search">
        <ul class="dropdown-menu" id="result">
        </ul>
      </form>
    </div>
  </div>
</nav>
<nav class="navbar mynav navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent2">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <?php
          $phase = new Phase();
          $results = $phase->read();
          while ($title = $results->fetch_assoc()) {
              $href = "/fwApp/api/Request.php/categorised.php?phase=" . $title["title"] . " ";
              echo "<li class='phase-links' id='" . $title["title"]. "' onclick='qs(`" . $title["title"]. "`)'> <a  class='nav-link' href='#phase=" . $title["title"] ."'>" . $title["title"] . "</a> </li>";
          }
          ?>

      </ul>
    </div>
  </div>
</nav>

    <div class="jumbotron" style="margin: 0">
      <div class="col container">

        <h1 class="display-3" id="question-sets-for">Phases</h1>
        <p class='lead'>1. Choose from any Phase Above</p>
        <p class='lead'>2. Apply a filter (Optional)</p>
      </div>
    </div>
        
    <section id="qsets" style="display: none" class="h-100">
        <div class="d-flex bd-highlight">
            <div class="p-2 bd-highlight shadow-lg" style="min-width: 320px;"> 
            <form class='form-check'>
              <div>
                <div>
                  <hr/>
                  <a class='btn' data-bs-toggle='collapse' href='#type' role='button' aria-expanded='false' aria-controls='type'>
                    <p class='link-primary'> ❯ Filter by type  </p> </a>
                  <div class='collapse' id='type'>
                    <ul style='list-style-type: none;'>
                    <?php
                    $phase = new Type();
                    $results = $phase->read();
                    while ($title = $results->fetch_assoc()) {
                        $id = $title["title"];
                    ?>
                      <li>
                        <input class='form-check-input filter-checks filter-checks-type'  type='checkbox' id='<?php echo $id; ?>' name='type[]' value='<?php echo $id; ?>'>
                        <label class='form-check-label' for='<?php echo $id; ?>'> <?php echo $id; ?> </label><br>
                      </li>
                    <?php
                    }
                    ?>
                    </ul>
                  </div>
                  <hr/>
                </div>
                <div>
                  <a class='btn' data-bs-toggle='collapse' href='#specialism' role='button' aria-expanded='false' aria-controls='specialism'>
                    <p class='link-primary'>❯ Filter by specialism </p>
                  </a>
                  <div class='collapse' id='specialism'>
                    <ul style='list-style-type: none;'>
                      <?php
                      $phase = new Specialism();
                      $results = $phase->read();
                      while ($title = $results->fetch_assoc()) {
                          $id = $title["title"];
                      ?>
                      <li>
                        <input class='form-check-input filter-checks filter-checks-specialism' type='checkbox' id='<?php echo $id; ?>' name='specialism[]' value='<?php echo $id; ?>'>
                        <label class='form-check-label' for='<?php echo $id; ?>'>     <?php echo $id; ?> </label><br>
                      </li>
                      <?php
                      }
                      ?>
                    </ul>
                  </div>
                  <hr/>
                </div>
                  </div>
                      <br>
            </form>
            </div>

          <?php
          /*<div>
            <p>Test</p>
            <div id="test_div"></div>
          </div>
          <script src="https://unpkg.com/react@17/umd/react.development.js" crossorigin></script>
          <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js" crossorigin></script>
          <script src="/fwApp/js/test.js" crossorigin></script>*/
          ?>
            
            <div class="bd-highlight">
             
                <div  class="row">

                    
                    <div class="bg-dark mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center col" style=" min-width: 400px; min-height: 70vh">
                        <div class="my-3 py-3 text-white ">
                            <h2 class="display-5">Categorised</h2>
                            <p class="lead" id="title-categorised"></p>
                        </div>
                        <div class="box-shadow mx-auto" style=" width: 80%; border-radius: 21px 21px 0 0;">
                            <ul class="" id = "qsCategoried">
                            </ul>
                        </div>
                    </div>
                    

                    
                    <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center col" style=" min-width: 400px; min-height: 70vh">
                        <div class="my-3 p-3">
                            <h2 class="display-5">Uncategorised</h2>
                            <p class="lead " id="title-uncategorised"></p>
                        </div>
                        <div class="box-shadow mx-auto" style="width: 80%; border-radius: 21px 21px 0 0;">
                            <ol class="" id = "qsUnCategoried">
                            </ol>
                        </div>
                    </div>
                
                </div>
            </div>

        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Questions</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <div id = "questionList">

            </div>
        </div>
    </div>
    </div>

    <?php if (!array_key_exists("developer", $_SESSION) || $_SESSION["developer"] !==  "developer") {
//      echo $debugbarRenderer->render();
    }?>
    
</body>
<script>

    var checkboxs = document.getElementsByClassName('filter-checks');
    Array.from(checkboxs).forEach(el => {
        el.addEventListener('change', function () {
            handleFilter()
        })
    });

    function handleFilter() {
        var checkboxesType = []
        var checkboxsType = document.getElementsByClassName('filter-checks-type');
        var checkboxesSpecialism = []
        var checkboxsSpecialism = document.getElementsByClassName('filter-checks-specialism');

        Array.from(checkboxsType).forEach(box => {
            if (box.checked) {
                console.log(box.value)
                checkboxesType.push(box.value)
                }
            }
        )
        Array.from(checkboxsSpecialism).forEach(box => {
            if (box.checked) {
                console.log(box.value)
                checkboxesSpecialism.push(box.value)
                }
            }
        )

        loadQuestionSets(checkboxesType, checkboxesSpecialism)
    }

    function getPhase() {
        const phase = document.getElementsByClassName("spec-active")[0].id
        console.log(phase);
        return phase;
    }

    function loadQuestionSets(checkboxesType, checkboxesSpecialism) {
        var phase = getPhase();
        var categoriedURL = new URL("http://www.uniquechange.com/fwApp/api/categorised.php?title=sf");
        var uncategoriedURL = new URL("http://www.uniquechange.com/fwApp/api/uncategorised.php");

        console.log(phase);
        if (checkboxesType.length != 0) {
            categoriedURL.searchParams.append('type', checkboxesType);
            uncategoriedURL.searchParams.append('type', checkboxesType);  
        }


        if (checkboxesSpecialism != 0) {
            categoriedURL.searchParams.append('specialism', checkboxesSpecialism);
            uncategoriedURL.searchParams.append('specialism', checkboxesSpecialism);
        }

        categoriedURL.searchParams.append('phase', phase);
        categoriedURL.searchParams.append('email', "<?php echo $_SESSION["email"]; ?>");
        uncategoriedURL.searchParams.append('phase', phase);
        uncategoriedURL.searchParams.append('email', "<?php echo $_SESSION["email"]; ?>");
        console.log(categoriedURL.href);
        console.log(uncategoriedURL.href);
        loadDoc(categoriedURL, qsCategoried);
        loadDoc(uncategoriedURL, qsUncategoried); 
    }
    
    var question_set_id = "";

    

    function loadDoc(url, func) {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() { func(this);}
        xhttp.withCredentials = true;
        xhttp.open("GET", url);
        xhttp.send();  
    }

    function qsUncategoried(xhttp) {
        var html = "";
        document.getElementById("qsets").style.display = "block";
        var data = JSON.parse(xhttp.responseText);
        titleCategory(data, "title-uncategorised")
        for (var data_qs in data) {
            var qs = data[data_qs];
            if (qs.length != 0 ) {
                html += "<li onclick='question(`" + qs.ID + "`)' class='list-unstyled m-1 bg-dark text-white rounded zoom p-2' data-bs-toggle='modal' data-bs-target='#exampleModal'>";
                html += "<span>" + qs.ID + ": </span> ";
                html += "<span>" + qs.questionSetName + "</span>";
                html += "</li>";
            }
        }
        document.getElementById("qsUnCategoried").innerHTML = html;
    }
    //
    // <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    //     Launch demo modal
    // </button>

    function titleCategory(data, id) {
        if (data.length == 0) {
            document.getElementById(id).innerHTML = "None available"
        } else {
            document.getElementById(id).innerHTML = "";
        }
        console.log(data);
    }

    function qsCategoried(xhttp) {
        var html = "";
        document.getElementById("qsets").style.display = "block";
        var data = JSON.parse(xhttp.responseText);

        titleCategory(data, "title-categorised")

        var categories = [];
        
        // This will collect categories
        for (var data_qs in data) {
            var qs = data[data_qs];
            if (qs.length != 0) {
                categories.push(qs.category);
            }
        }
        categories = Array.from(new Set(categories));
        
        html = "";
        document.getElementById("qsets").style.display = "block";
        category_id = 0
        for (var int in categories) {
            var category = categories[int];
            console.log(category);
            html += "<li class='list-unstyled'>";
            html += "<button class='btn link-primary rounded m-1 bg-light' data-bs-toggle='collapse' href='#collapse" + category_id + "' aria-expanded='true' aria-controls='collapse" + category_id + "' > <small>❯ " + category + "</small></button>"
            html += "<ul class='collapse show'  id='collapse" + category_id + "'>";
            for (var idx in data) {
                var qs = data[idx];
                if (qs.length != 0) {
                    if (qs.category == category) {
                        html += "<li onclick='question(`" + qs.questionSetID + "`)' class='list-unstyled bg-light m-1 rounded zoom p-2' data-bs-toggle='modal' data-bs-target='#exampleModal'>";
                        html += "<span>" + qs.questionSetID + ": </span>";
                        html += "<span>" + qs.questionSetName + "</span>";
                        html += "</li>"; 
                    }  
                }
            }
            html += "</ul>";
            html += "</li>";
            category_id += 1;
        }

        document.getElementById("qsCategoried").innerHTML = html;
    }

    function questionList(xhttp) {
        var html = "<div id = 'modal-body'>";
        html += "<ol class='list-group m-5' id = 'questionList'>"
        var data = JSON.parse(xhttp.responseText);
        console.log(data);
        for (var data_qs in data) {
            var qs = data[data_qs];
            if (qs.length != 0 ){
                html += "<li class='list-group-item'>";
                html += "<span>" + qs.ID + ": </span>";
                html += "<span>" + qs.question + "</span>";
                html += "</li>";
            }
        }
        html += "</ol>";
        html += "</div>";

        html += "<div class='modal-footer'>"
        html += "<form action='/fwApp/html/question.php' method='get'/>";
        html += "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>";
        html += "<span> </span>"
        html += "<button class='btn btn-primary' name = 'id' value= '" + question_set_id + "'> Use this question set! </button>";
        html += "</form>";
        html += "</div>"

        document.getElementById("questionList").innerHTML = html;
    }

    function makeNavItemActive(phase) {
        var navs = document.getElementsByClassName("phase-links");
        for (var i = 0; i < navs.length; i++) 
        {
            var el = navs[i];
            el.className = "phase-links"
            el.style = ""
        }

        var activeNav = document.getElementById(phase);
        document.getElementById("question-sets-for").innerHTML = phase;
        activeNav.className = "phase-links active spec-active";
        activeNav.style = "background-color: gray";
    }

    function qs(phase) {
        makeNavItemActive(phase);
        handleFilter();
    }


    window.onload = function() {
        makeNavItemActive("Starters");
        loadQuestionSets([], [])
    }

    function question(id) {
        const url = "/fwApp/api/questions.php?id=" + id;
        question_set_id  = id;
        loadDoc(url, questionList);
    }
</script>

</html>
