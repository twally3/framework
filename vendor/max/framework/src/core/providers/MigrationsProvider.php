<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class MigrationsProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$app->bind('migrations', 'Framework\Core\Database\Migrations');
	}
	
}