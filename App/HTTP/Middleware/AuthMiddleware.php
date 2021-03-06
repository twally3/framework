<?php

namespace App\HTTP\Middleware;

use Framework\Core\Support\MiddlewareInterface;
use Framework\Core\HTTP\Request;
use \Route;

Class AuthMiddleware implements MiddlewareInterface {

	public function handle(Request $request) {

		if (!\Auth::check()) {
			Route::redirect('/login');
		}
		
		return;

	}

}