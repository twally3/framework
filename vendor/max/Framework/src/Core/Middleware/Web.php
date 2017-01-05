<?php

namespace Framework\Core\Middleware;

use Framework\Core\Support\MiddlewareInterface;
use Framework\Core\HTTP\Request;
use \Session;
use \Route;

Class Web implements MiddlewareInterface {

	/**
	 * Checks if the CSRF token is set in a get request
	 * @param  Request $request Request object
	 * @return void
	 */
	public function handle(Request $request) {
		if ($request->_token != Session::get('csrf_token')) {
			Route::redirect('/');
		}

	    return;
	}

}