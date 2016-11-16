<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once '../vendor/max/Framework/src/core/Helpers/helpers.php';

set_error_handler("customErrorCatch");
register_shutdown_function('fatalErrorShutdownHandler');

try {

  include '../app/init.php';
  

  include '../app/http/routes.php';
  Framework\Core\HTTP\Route::submit();

} catch(Exception $e) {
  if (LOGGING) {
    $error = $e->getMessage();
    $txt = file_get_contents("../app/handlers/errors/error.txt");

    $myfile = fopen("../app/handlers/errors/error.txt", "w");
    $txt .= time() . " | $error \n";
    fwrite($myfile, $txt);
    fclose($myfile);
  }

  if (DEBUGGING) {
    var_dump($e->getMessage());
  }

  die;
}
