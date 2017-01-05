<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class ValidatorProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$app->singleton('validator', 'Framework\Core\HTTP\Validator');
	}
}