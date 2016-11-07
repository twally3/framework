<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'Config/config.php';
require_once '../app/core/helpers.php';

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
