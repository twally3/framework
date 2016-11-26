<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class SchemaProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$app->bind('schema', 'Framework\Core\Database\Schema');
	}
}