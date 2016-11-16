<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);


try {

  include '../app/init.php';
  
  set_error_handler("customErrorCatch");
  register_shutdown_function('fatalErrorShutdownHandler');

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
