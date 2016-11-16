<?php namespace App\HTTP\Middleware;

class Thing {
  // public function handle(Request $request, Closure $next) {
  //   echo "handled";
  // }

	public function handle() {
    echo "Thing handled";
  }
}
