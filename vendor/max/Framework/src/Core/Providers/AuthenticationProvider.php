<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class AuthenticationProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$app->singleton('authentication', 'Framework\Core\Security\Authentication');
	}
}