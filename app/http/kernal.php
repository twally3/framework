<?php namespace App\HTTP;

use Framework\Core\HTTPKernal as HTTPKernal;

Class Kernal extends HTTPKernal {
	protected static $middleware = [
		// Middleware goes here
	];

	protected static $routeMiddleware = [
		// route middleware goes here
		'http' => 'App\HTTP\Middleware\Web::class'
		
	];
}