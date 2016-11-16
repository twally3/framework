<?php


use Framework\Core\ORM\ORM as ORM;
use Framework\Core\HTTP\Session as Session;
use Framework\Core\Database\Database as Database;

require_once 'config/config.php';
require_once 'config/db_config.php';

require_once '../vendor/max/Framework/src/core/HTTPKernal.php';
require_once '../vendor/max/Framework/src/core/ORM/ORM.php';
require_once '../vendor/max/Framework/src/core/Views/Tea.php';
require_once '../vendor/max/Framework/src/core/Database/Database.php';

require_once '../vendor/max/Framework/src/core/HTTP/file.php';
require_once '../vendor/max/Framework/src/core/HTTP/Requests.php';
require_once '../vendor/max/Framework/src/core/HTTP/Routing.php';
require_once '../vendor/max/Framework/src/core/HTTP/controller.php';
require_once '../vendor/max/Framework/src/core/HTTP/Sessions.php';

require_once 'http/Kernal.php';
// require_once 'Database/core/Database.php';
// Remember to fix this!


  session::start();
  if (!Session::isset('csrf_token')) {
    session::set('csrf_token', base64_encode(openssl_random_pseudo_bytes(32)));
  }
  if (!Session::isset('flash')) {
    session::set('flash', []);
  }
  Database::construct();
  ORM::setupModels();
