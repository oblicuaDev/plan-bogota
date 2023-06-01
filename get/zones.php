<?php 
    include "../includes/sdk_import.php";
    include "../includes/plan-bogota.php";
    $pb = new planbogota(isset($_GET["lang"]) ? $_GET["lang"]  : 'es' );
    $localidades = $pb->getTaxs($_GET["taxName"]);
    echo json_encode($localidades);
?>