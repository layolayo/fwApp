<?php
// Start the session
session_start();

// Echo session variables that were set on previous page
if ($_SESSION["authenticated"] !==  "authenticated") {
    header("Location: login");
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>User</title>
    <meta name="description" content="home page">
    <meta name="keywords" content="writing author book facilitated ">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="../../css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="../../js/bootstrap/bootstrap.min.js"></script>
    <link href="../../css/nav.css" rel="stylesheet">
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
                <li class="nav-item active">
                    <a class="nav-link" href="">Account</a>
                </li>
                </ul>
                <form class="form-inline my-2 my-lg-0 dropdown">
                    <input class="form-control mr-sm-2 " type="search" id="search" placeholder="Search" aria-label="Search">
                    <ul class="dropdown-menu" id="result">
                    </ul>
                </form>
            </div>
    </nav>

    <div class="container p-2">
        <?php
        // Echo session variables that were set on previous page
        if ($_SESSION["authenticated"] ===  "authenticated") {
            echo "<h1> Welcome " .$_SESSION["email"]. " </h1>";
        }
        ?>
        <a class="btn btn-secondary m-2" href="../logout/"> Logout </a>

        <ul class="list-group m-2">
            <?php
            //../../api/Request.php/userqs/?email=$email
            $email = $_SESSION["email"];
            $file = "http://www.uniquechange.com/fwApp/api/Request.php/userqs/?email=$email";
            $content = file_get_contents($file);
            $data = json_decode($content, true);
            if ($content) {
                foreach($data as $qs) {
                    $id = $qs["ID"];
                    $title = $qs["title"];
                    echo "<li class='list-group-item'><a href='../question/?id=$id'>$id) $title </a></li>";
                }
            }
            ?>
        </ul>

    </div>




</body>
</html>