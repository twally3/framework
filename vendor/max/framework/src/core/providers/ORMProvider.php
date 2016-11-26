<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;
use \ORM;

class ORMProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$this->app = $app;
		$app->singleton('orm', 'Framework\Core\ORM\ORM');
		// $x = $app->resolve('orm');
		// $x::setupModels();
	}

	public function boot() {
		ORM::setupModels($this->app);
	}
}