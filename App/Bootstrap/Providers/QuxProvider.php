<?php 

namespace App\Bootstrap\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class QuxProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$app->bind('qux', 'App\Bootstrap\Includes\Qux');
	}
}