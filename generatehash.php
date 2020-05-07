<?php

 function getSignature($time,$body,$method,$path,$secret)
  {
    $_encryptBody = '';
    if ($method == "GET") {
      $_encryptBody = $time."\r\n".$method."\r\n".$path."\r\n\r\n";
    } else {
      $_encryptBody = $time."\r\n".$method."\r\n".$path."\r\n\r\n".$body;
    }
    return hash_hmac("sha256", $_encryptBody, $secret);
  }


function buildHeader($timesame,$key,$signature,$county)
  {
   
    return [
      "X-Request-ID" => uniqid(),
      "Content-type" => "application/json; charset=utf-8",
      "Authorization" => "hmac ".$key.":".$timesame.":".$signature,
      "Accept"=> "application/json",
      "X-LLM-Country"=> $county
    ];
  }


$time = time() * 1000;


$body = array(
  "scheduleAt" => gmdate('Y-m-d\TH:i:s\Z', time() + 60 * 30), // ISOString with the format YYYY-MM-ddTHH:mm:ss.000Z at UTC time
  "serviceType" => "MOTORCYCLE",                              // string to pick the available service type
  "specialRequests" => array(),                               // array of strings available for the service type
  "requesterContact" => array(
    "name" => "Catague",
    "phone" => "+63xxxxxxxx"                                  // Phone number format must follow the format of your country
  ),  
  "stops" => array(
    array(
      "location" => array("lat" => "14.568130", "lng" => "120.986343"),
      "addresses" => array(
        "en_PH" => array(
          "displayString" => "Malate, Manila, Metro Manila, Philippines",
          "country" => "PH"                                   // Country code must follow the country you are at
        )   
      )   
    ),  
    array(
      "location" => array("lat" => "14.559820", "lng" => "121.080643"),
      "addresses" => array(
        "en_PH" => array(
          "displayString" => "Maltese Philippines, Pasig, Metro Manila, Philippines",
          "country" => "PH"                                   // Country code must follow the country you are at
        )   
      )   
    )   
  ),  
  "deliveries" => array(
    array(
      "toStop" => 1,
      "toContact" => array(
        "name" => "travis cat",
        "phone" => "+639058086521"                              // Phone number format must follow the format of your country
      ),  
      "remarks" => "ORDER #: 1234, ITEM 1 x 1, ITEM 2 x 2"
    )   
  )   
);

$bodyjsonencode = json_encode((object)$body);

$t =  getSignature($time,$bodyjsonencode,'POST','/v2/quotations',<api secret key>);

$apikey = 'your api key';

var_dump(buildHeader($time,$apikey,$t,'PH')) ;




?>
