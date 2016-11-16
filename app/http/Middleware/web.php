<?php namespace App\HTTP\Middleware;

use Framework\Core\HTTP\Session as Session;
use Closure;

class Web {
  // public function handle(Request $request, Closure $next) {
  //   echo "handled";
  // }

	public function handle($request) {
		if ($request->_token != Session::get('csrf_token')) {
			redirect('/testing/requests');
		}

    return;
  }
}
