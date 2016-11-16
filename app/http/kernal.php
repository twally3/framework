<?php

use Framework\Core\HTTPKernal as HTTPKernal;

Class Kernal extends HTTPKernal {
	protected static $middleware = [
		// Middleware goes here
	];

	protected static $routeMiddleware = [
		// route middleware goes here
		'http' => 'app/http/middleware/web.php'
		
	];
}