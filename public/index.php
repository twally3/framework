<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

$autoload = require __DIR__ . '/../vendor/max/framework/src/core/foundation/autoload.php';

$app = require_once  __DIR__ . '/../app/bootstrap/app.php';

$kernel = $app->resolve('httpkernel');
$request = $app->resolve('request');

$response = $kernel->handle($request);


// send response!