<?php
  // Storing 'getmac' value in $MAC
  $MAC = exec('getmac');

  // Updating $MAC value using strtok function,
  // split character of strtok is defined as a space
  // strtok is used to split the string into tokens
  // because getmac returns transport name after
  $MAC = strtok($MAC, ' ');    

  // Replace character(-) with character(:)
  $MAC = str_replace('-',':',$MAC);
