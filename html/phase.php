<?php
// Start the session
session_start();

// Ensure that the user is logged in
if (!array_key_exists("authenticated", $_SESSION) || $_SESSION["authenticated"] !==  "authenticated") {
    header("Location: /fwApp/html/login.html");
}
?>

<!doctype html>
<html lang="en">

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="/fwApp/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="/fwApp/js/bootstrap/bootstrap.min.js"></script>
    <link href="/fwApp/css/nav.css" rel="stylesheet">
    <script src="/fwApp/js/search.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="phase">Phase <span class="sr-only">(current)</span></a>
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
    
    <div>
        <div class="navbar mynav navbar-expand-lg navbar-light bg-light">
            <ul class="navbar-nav"  id = "my-nav">
                <?php 
                $file = "http://uniquechange.com/fwApp/api/Request.php/phase/";
                $content = file_get_contents($file);
                $data = json_decode($content, true);
                if ($content) {
                    foreach($data as $title) {
                        $href = "/fwApp/api/Request.php/categoried/?phase=" .$title["title"]. " ";
                        echo "<li class='phase-links' id='" .$title["title"]. "' onclick='qs(`" .$title["title"]. "`)'> <a  class='nav-link' href='#phase=". $title["title"] ."'>" . $title["title"] . "</a> </li>";
                    }
                }
                ?>
                
            </ul>
        </div>
        
        <div class="jumbotron" style="margin: 0">
            <div class="col container">
                
                <h1 class="display-3" id="question-sets-for">Phases</h1>
                <p class='lead'>1. Choose from any Phase Above</p>
                <p class='lead'>2. Apply a filter (Optional)</p>
            </div>
        </div>
    </div>
        
    <section id="qsets" style="display: none";>
        <div class="d-flex bd-highlight mb-3">
            <div class="p-2 bd-highlight shadow-lg" style="min-width: 320px;"> 
            <form class='form-check form-switch'>
                <?php 
                    $file = "http://uniquechange.com/fwApp/api/Request.php/type/";
                    $content = file_get_contents($file);
                    $data = json_decode($content, true);

                    echo "<div>";

                    echo "<div>";

                    echo "<hr/>";
                    echo "<a class='btn' data-toggle='collapse' href='#type' role='button' aria-expanded='false' aria-controls='type'>
                    <p> ❯ Filter by type  </p> </a>";

                    echo "<div class='collapse' id='type'>";
                    echo "<ul>";
                    if ($content) {
                        foreach($data as $title) {
                            $id = $title["title"];
                            echo "<li>";
                            echo "<input class='form-check-input filter-checks filter-checks-type'  type='checkbox' id='$id' name='type[]' value='$id'>";
                            echo "<label class='form-check-label' for='$id'> $id </label><br>";
                            echo "</li>";
                        }
                    }
                    echo "</ul>";
                    echo "</div>";
                    echo "<hr/>";

                    echo "</div>";


                    $file = "http://uniquechange.com/fwApp/api/Request.php/specialism/";
                    $content = file_get_contents($file);
                    $data = json_decode($content, true);

                    echo "<div>";

                    echo "<a class='btn' data-toggle='collapse' href='#specialism' role='button' aria-expanded='false' aria-controls='specialism'>
                    <p>❯ Filter by specialism </p> </a>";
                    
                    echo "<div class='collapse' id='specialism'>";
                    echo "<ul>";
                    if ($content) {
                        foreach($data as $title) {
                            $id = $title["title"];
                            echo "<li>";
                            echo "<input class='form-check-input filter-checks filter-checks-specialism' type='checkbox' id='$id' name='specialism[]' value='$id'>";
                            echo "<label class='form-check-label' for='$id'>     $id </label><br>";
                            echo "</li>";
                        }
                    }
                    echo "</ul>";
                    echo "</div>";
                    echo "<hr/>";

                    echo "</div>";

                    echo "</div>";
                    echo "<br>";
                ?>
            </form>
            </div>
            
            <div class="p-2 bd-highlight">
             
                <div  class="row">

                    
                    <div class="bg-dark mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center col" style=" min-width: 400px; min-height: 70vh">
                        <div class="my-3 py-3 text-white ">
                            <h2 class="display-5">Categorised</h2>
                            <p class="lead" id="title-categorised"></p>
                        </div>
                        <div class="box-shadow mx-auto" style=" width: 80%; border-radius: 21px 21px 0 0;">
                            <ul class="", id = "qsCategoried">
                            </ul>
                        </div>
                    </div>
                    

                    
                    <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center col" style=" min-width: 400px; min-height: 70vh">
                        <div class="my-3 p-3">
                            <h2 class="display-5">Uncategorised</h2>
                            <p class="lead " id="title-uncategorised"></p>
                        </div>
                        <div class="box-shadow mx-auto" style="width: 80%; border-radius: 21px 21px 0 0;">
                            <ol class="", id = "qsUnCategoried">
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
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <div id = "questionList">

            </div>
        </div>
    </div>
    </div>
    
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
        var categoriedURL = new URL("/fwApp/api/Request.php/categoried/");
        var uncategoriedURL = new URL("/fwApp/api/Request.php/uncategoried/");

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
        uncategoriedURL.searchParams.append('phase', phase);
        console.log(categoriedURL.href);
        console.log(uncategoriedURL.href);
        loadDoc(categoriedURL, qsCategoried);
        loadDoc(uncategoriedURL, qsUncategoried); 
    }
    
    var question_set_id = "";

    

    function loadDoc(url, func) {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() { func(this);}
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
                html += "<li onclick='question(`" + qs.ID + "`)' class='list-unstyled m-1 bg-dark text-white rounded zoom p-2' data-toggle='modal' data-target='#exampleModal'>";
                html += "<span>" + qs.ID + ": </span> ";
                html += "<span>" + qs.questionSetName + "</span>";
                html += "</li>";
            }
        }
        document.getElementById("qsUnCategoried").innerHTML = html;
    }

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
            html += "<button class='btn btn-link rounded m-1 bg-light' data-toggle='collapse' data-target='#collapse" + category_id + "' aria-expanded='true' aria-controls='collapse" + category_id + "' > <small>❯ " + category + "</small></button>"
            html += "<ul class='collapse show'  id='collapse" + category_id + "'>";
            for (var idx in data) {
                var qs = data[idx];
                if (qs.length != 0) {
                    if (qs.category == category) {
                        html += "<li onclick='question(`" + qs.questionSetID + "`)' class='list-unstyled bg-light m-1 rounded zoom p-2' data-toggle='modal' data-target='#exampleModal'>";
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
        html += "<ol class='list-group m-5', id = 'questionList'>"
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
        html += "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
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
        document.getElementById("question-sets-for").innerHTML = phase + ": Questions sets";
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
       var url = "/fwApp/api/Request.php/questions?id=" + id + " ";
       question_set_id  = id;
       loadDoc(url, questionList);
    }
</script>

</html>
