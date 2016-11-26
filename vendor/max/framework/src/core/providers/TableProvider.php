<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class TableProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$app->bind('table', 'Framework\Core\Database\Table');
	}
}