<?php
    include "../includes/sdk_import.php";
    include "../includes/mice.php";  $mice = new mice($_GET["lang"]);

    //echo "filtro->".$_POST['filter'];
    $result = $mice->getfilters($_POST['filter']);
    echo json_encode($result);
?>