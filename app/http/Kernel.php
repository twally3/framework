<?php

namespace App\HTTP;

use Framework\Core\Foundation\HTTP\HTTPKernel;

Class Kernel extends HTTPKernel {

	protected $routeMiddleware = [

		'web' => \Framework\Core\Middleware\Web::class

	];

}