<?php 
    
// input data  through array
$array = Array (
    "0" => Array (
        "id" => "7020",
        "name" => "Bobby",
        "Subject" => "Java"
    ),
    "1" => Array (
         "id" => "7021",
        "name" => "ojaswi",
        "Subject" => "sql"
    )
);
  
$json = json_encode($array);

$file_path = '../reviews/1064.json';
  
// Checking whether file exists or not
if (!file_exists($file_path)) {
  
    // Create a new file or direcotry
    mkdir($file_path, 0777, true);
    file_put_contents($file_path, $json);
    echo "$json";
}
else {
    file_put_contents($file_path, $json);
    echo "$json";
}
?>