<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class SessionRequestProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$app->singleton('sessionrequest', 'Framework\Core\HTTP\SessionRequest');
	}
	
}