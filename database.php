<?php
function openDB() {
    $link= mysqli_connect("localhost", "root", "") or die("Kapcsolódás sikertelen" . mysqli_error());
    mysqli_select_db($link,"hf_d5l5ke");
    mysqli_query ($link, "set character_set_results='utf8'");
    mysqli_query ($link, "set character_set_client='utf8'");
    return $link;   
}

function closeDB($link) {
    mysqli_close($link);
}
?>