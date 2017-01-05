<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class JWTProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		
		// Bind app to container here!
		$app->singleton('jwt', 'Framework\Core\Security\JWT');

	}

	public function boot() {

		// If boot exists it will run once all middleware has been loaded!

	}
}