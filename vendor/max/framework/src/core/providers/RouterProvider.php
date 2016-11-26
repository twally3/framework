<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class RouterProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$app->singleton('router', 'Framework\Core\HTTP\Router');
	}
}