<?php
  include 'mac.php';
  $mac_address = $MAC;

  $url = "https://api.macvendors.com/" . urlencode($mac_address);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($ch);
  if($response) {
    if (strpos($response, '{') !== FALSE) {
      $result = 'Not Found';
    } else {
      $result = "Vendor: $response";
    }
  } else {
      $result = "Not Found";
  }