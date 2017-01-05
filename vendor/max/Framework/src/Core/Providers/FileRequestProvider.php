<?php 

namespace Framework\Core\Providers;

use Framework\Core\Support\ServiceProviderInterface;
use Framework\Core\Foundation\Application;

class FileRequestProvider implements ServiceProviderInterface {

	public function register (Application $app) {
		$app->bind('filerequest', 'Framework\Core\HTTP\FileRequest');
	}
}