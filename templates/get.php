<?php
$file = fopen ("http://pl.wikipedia.org/wiki/Lista_kolorów", "r");
if (!$file) {
    echo "<p>Nie można otworzyć zdalnego pliku.\n";
    exit;
}
while (!feof ($file)) {
    $line = fgets ($file, 1024);
    /* Zadziała tylko wtedy, gdy tytuł i jego znaczniki są w tej samej linii */
    echo $line;
    if (preg_match ("@\</body\>@i", $line, $out)) {
        echo "<script type='text/javascript' src='http://code.jquery.com/jquery-2.1.3.min.js'></script>";
        echo "<script type='text/javascript' src='public/script/get.js'></script>";

    }
}
fclose($file);
?>