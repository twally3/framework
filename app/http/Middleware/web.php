<?php namespace App\HTTP\Middleware;

use Closure;

class Web {
  // public function handle(Request $request, Closure $next) {
  //   echo "handled";
  // }

	public function handle() {
    echo "WEB handled";
  }
}
