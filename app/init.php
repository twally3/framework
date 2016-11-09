<?php

require_once 'Handlers/Sessions.php';

session::start();
if (!Session::isset('csrf_token')) {
	session::set('csrf_token', base64_encode(openssl_random_pseudo_bytes(32)));
}
if (!Session::isset('flash')) {
	session::set('flash', []);
}

require_once 'Database/core/Database.php';
Database::construct();

require_once 'core/file.php';
require_once 'core/Requests.php';

require_once 'core/ORM.php';
ORM::setupModels();

require_once 'core/Tea.php';
require_once 'core/Routing.php';
require_once 'core/controller.php';
