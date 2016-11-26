<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class RequestProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$app->singleton('request', 'Framework\Core\HTTP\Request');
	}
}