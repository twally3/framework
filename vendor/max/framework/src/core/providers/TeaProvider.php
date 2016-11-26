<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class TeaProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$app->singleton('tea', 'Framework\Core\Render\Tea');
	}
}