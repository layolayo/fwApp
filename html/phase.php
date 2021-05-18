<!doctype html>
<html>

<head>
    <title>Our Funky HTML Page</title>
    <meta name="description" content="Our first page">
    <meta name="keywords" content="html tutorial template">
</head>

<body>
    <!-- if statements -->
    <!-- file_get_contents($filename) -->
    <!-- loop through json -->
    <?php 
    $file = "http://www.uniquechange.com/fwApp/api/titles.php/";
    $content = file_get_contents($file);
    if ($content) {
        $data = json_decode($content, true);
        $titles = array();
        foreach($title as $data) {
            $titles = $titles['title'];
            
            echo print_r(array_values($titles['title']));
        }
    }
    echo print_r(array_values(json_decode($content)));
    echo print_r(array_values($titles));
    ?>
    <nav>
        <ol>
            <li> About </li>
            <li> Account </li>
        </ol>
    </nav>

    <section>
        <button onclick="location.href='preset.html'" type="button">Use a pre-set process?</button>
        <button onclick="location.href='phase.html'" type="button">Choose at each phase?</button>
        <button onclick="location.href='stuck.html'" type="button">Respond to ‘stuckness’?</button>
    </section>
</body>

</html>