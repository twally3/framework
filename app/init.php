<?php

require_once 'Database/core/Database.php';
Database::construct();

require_once 'core/file.php';
require_once 'core/Requests.php';

require_once 'core/ORM.php';
ORM::setupModels();

require_once 'Handlers/Sessions.php';
require_once 'core/Tea.php';
require_once 'core/Routing.php';
require_once 'core/controller.php';
