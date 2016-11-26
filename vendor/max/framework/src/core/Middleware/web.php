<?php

namespace Framework\Core\Middleware;

use Framework\Core\Support\MiddlewareInterface;
use Framework\Core\HTTP\Request;
use \Session;
use \Route;

Class Web implements MiddlewareInterface {

	public function handle(Request $request) {
		if ($request->_token != Session::get('csrf_token')) {
			Route::redirect('/');
		}

	    return;
	}

}