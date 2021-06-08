<!doctype html>
<html>

<head>
    <title>Our Funky HTML Page</title>
    <meta name="description" content="Our first page">
    <meta name="keywords" content="html tutorial template">
</head>

<script>
    function loadDoc(url, cFunction) {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() { cFunction(this);}
        xhttp.open("GET", url);
        xhttp.send();  
    }

    function qs(php) {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            console.log(php);
            var html = "";
            var data = JSON.parse(this.responseText);
            console.log(data);
            for (var data_qs in data) {
                var qs = data[data_qs];
                html += "<li>";
                html += "<span>" + qs.ID + "</span> <br>";
                html += "<span>" + qs.title + "</span>";
                html += "</li>";
                document.getElementById("qsCategoried").innerHTML = html;
            }
        };
        console.log(html);
        xhttp.open("GET", php);
        xhttp.send();   
    }

    function qsCategoried(xhttp) {
        var html = "";
        var data = JSON.parse(xhttp.responseText);
        console.log(data);
        for (var data_qs in data) {
            var qs = data[data_qs];
            html += "<li>";
            html += "<span>" + qs.ID + "</span> <br>";
            html += "<span>" + qs.title + "</span>";
            html += "</li>";
            document.getElementById("qsCategoried").innerHTML = html;
        }
    }


</script>


<body>
    <!-- if statements -->
    <!-- file_get_contents($filename) -->
    <!-- loop through json -->
    <nav>
        <ol>
            <li> About </li>
            <li> Account </li>
        </ol>
    </nav>

    <ol>
        <?php 
        $file = "http://www.uniquechange.com/fwApp/api/titles.php/";
        $content = file_get_contents($file);
        $data = json_decode($content, true);
        if ($content) {
            foreach($data as $title) {
                $href = "/fwApp/api/Request.php/categoried/?phase=" .$title["title"]. " ";
                //echo "<li> <button onclick='qs(`" . $href ."`)'>" . $title["title"] . "</button> </li>";
                echo "<li> <button onclick='loadDoc(`" .$href. "`,". "qsCategoried". ")'>" . $title["title"] . "</button> </li>";
                
            }
        }
        ?>
    </ol>

    <ol id = "qsCategoried">
    </ol>

</body>

</html>