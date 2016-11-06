<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

function debugArray($array = []) {
  echo '<pre>';
  print_r($array);
  echo '</pre>';
}

function customErrorCatch($num, $msg, $file, $line) {
  throw new Exception('Error: '. $num . ' ' . $msg. ' in file '. $file. ' on line '. $line);
}

set_error_handler("customErrorCatch");

try {
  // throw new Exception('THE SHIT IS FUCKED');
  include '../app/init.php';

  session::start();
  session::set('csrf_token', base64_encode(openssl_random_pseudo_bytes(32)));

  include '../app/http/routes.php';
  Route::submit();

} catch(Exception $e) {
  $error = $e->getMessage();
  $txt = file_get_contents("../app/handlers/errors/error.txt");

  $myfile = fopen("../app/handlers/errors/error.txt", "w");
  $txt .= time() . " | $error \n";
  fwrite($myfile, $txt);
  fclose($myfile);

  if (DEBUGGING) {
    var_dump($e->getMessage());
  }

  die;
}
