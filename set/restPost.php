<?php
include "../includes/functions.php";
//Client Message
extract($_POST);
$array = array();
$cid = 0;
$curl = curl_init();

function get_rand_alphanumeric($length) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
  $characters_length = strlen($characters);
  $rand_id = '';
  for ($i = 0; $i < $length; $i++) {
    $rand_id .= $characters[rand(0, $characters_length - 1)];
  }
  return $rand_id;
}

$uid = "pb".get_rand_alphanumeric(6);

$theprice = str_replace(".","",$_POST["uprice"]);
$postfields = '{
  "dataset":"books_pb",
  "table":"bookings",
  "row":{
          "uniqid": '.$cid.',
          "name":"'.$_POST["uname"].'",
          "email":"'.$_POST["uemail"].'",
          "serviceid":'.$_POST["uofertaid"].',
          "service":"'.$_POST["uoferta"].'",
          "phone":"'.$_POST["uphone"].'",
          "companyid":'.$_POST["ucompanyid"].',
          "company":"'.$_POST["ucompanyname"].'",
          "datet":"'.date("Y-m-d").'T'.date("h:i:s").'",
          "version":4,
          "price":"'.$theprice.'",
          "ocategoryid":'.$_POST["ocategoryid"].', 
          "ocategory":"'.$_POST["ocategory"].'",
          "ccategoryid":'.$_POST["ccategoryid"].',
          "ccategory":"'.$_POST["ccategory"].'",
          "guests":"'.$_POST["numberPersons"].'",
          "bookid":"'.$uid.'"
          }
}';


curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://cloud.bogotadc.travel/bigquery/feedtable/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$postfields,
  CURLOPT_HTTPHEADER => array(
    'auth: bogota-clean-emblem-367014-8028d5d34b0c',
    'project: clean-emblem-367014',
    'Content-Type: application/json'
  ),
));
$responsebq = curl_exec($curl);
curl_close($curl);

if($responsebq != ""){
  
  $ID = $uid;
  $date = new DateTime();
  $date->setTimeZone(new DateTimeZone("America/Bogota"));
  $thetime = $date->format('d/m/Y - H:i:s');
  

  $to = $uemail;
  $params = "{\"PBSERIAL\":\"$ID\",\"PBCOMPANY\":\"$ucompanyname\",\"PBOFFER\":\"$uoferta\",\"PBMAIL\":\"$ucompanyemail\",\"PBTEL\":\"$ucompanyphone\",\"PBLINK\":\"$ucompanylink\"},\"templateId\":63}";
  sendNotification($to, $params);
  
  //Admin Message
  $to = "planbogota@idt.gov.co";
  $params = "{\"PBNAME\":\"$uname\",\"PBSERIAL\":\"$ID\",\"PBOFFER\":\"$uoferta\",\"PBCOMPANY\":\"$ucompanyname\",\"PBMAIL\":\"$uemail\",\"PBTEL\":\"$uphone\",\"PBPRICE\":\"$uprice\"},\"templateId\":64}";
  sendNotification($to, $params);
  
  //Company Message
  $to = $ucompanyemail;
  $params = "{\"PBNAME\":\"$uname\",\"PBSERIAL\":\"$ID\",\"PBOFFER\":\"$uoferta\",\"PBMAIL\":\"$uemail\",\"PBTEL\":\"$uphone\",\"PBPRICE\":\"$uprice\"},\"templateId\":62}";
  sendNotification($to, $params);
  
  
  
  $array['message'] = 1;
  $array['insertmessage'] = $responsebq;
  function wh_log($log_msg)
  {
      $log_filename = 'log';
      if (!file_exists($log_filename)) 
      {
          // create directory/folder uploads.
          mkdir($log_filename, 0777, true);
      }
      $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
      file_put_contents($log_file_data, $log_msg . ',', FILE_APPEND);
  }
   
  wh_log($postfields);
  
  echo json_encode($array);
}else{
  $array['message'] = 0;
}
