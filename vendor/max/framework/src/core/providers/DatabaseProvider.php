<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class DatabaseProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$app->singleton('database', 'Framework\Core\Database\Database');
	}
}