<?php

namespace App\HTTP;

use Framework\Core\Foundation\HTTP\HTTPKernel;

Class Kernel extends HTTPKernel {

	protected $routeMiddleware = [

		'web' => \Framework\Core\Middleware\Web::class,
		'auth' => \App\HTTP\Middleware\AuthMiddleware::class,
		'authRedirect' => \App\HTTP\Middleware\RedirectIfAuthenticatedMiddleware::class

	];

}