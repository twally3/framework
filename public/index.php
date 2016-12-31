<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

$autoload = require __DIR__ . '/../vendor/max/Framework/src/Core/Foundation/autoload.php';
require_once  __DIR__ . '/../App/Config/keys.php';
$app = require_once  __DIR__ . '/../App/Bootstrap/app.php';

$kernel = $app->resolve('httpkernel');
$request = $app->resolve('request');

$response = $kernel->handle($request);


// send response!