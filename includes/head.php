<?php 
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'es';
if (file_exists("includes/bogota.php")) { 
    include "includes/bogota.php";
    $project_base = "";
}else{
  $include_level = "../";  
  $project_base = "/plan-bogota/";
  $project_folder = "plan-bogota";
  include "../includes/header.php";
}
  $bogota = new bogota(isset($_GET["lang"]) ? $_GET["lang"]  : 'es' );
  include "includes/plan-bogota.php";
  $pb = new planbogota(isset($_GET["lang"]) ? $_GET["lang"]  : 'es' );

?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="theme-color" content="#00857f" />
        <meta name="twitter:card" value="summary" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="MICE - BogotaDC.travel" />
        <meta property="og:url" content=" url" />
        <meta property="og:image" content="img/ventajas.jpg" />
        <meta property="og:description" content="description" />
        <title>Plan Bogotá - BogotaDC.travel</title>
        <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"
    />
      <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
      <link rel="stylesheet" href="<?=$project_base?>css/styles.css?v=<?=time()?>" />
        <link rel="canonical" href="url" />
    <meta name="description" content="description" /><script>
    </script>
    </head>