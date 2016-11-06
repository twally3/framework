<?php

require_once 'Config/config.php';
require_once 'core/Database.php';
Database::construct();

require_once 'core/ORM.php';
ORM::setupModels();

require_once 'Handlers/Sessions.php';
require_once 'core/Tea.php';
require_once 'core/Routing.php';
require_once 'core/controller.php';
